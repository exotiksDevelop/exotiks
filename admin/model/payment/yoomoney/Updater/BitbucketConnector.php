<?php

class BitbucketConnector
{
    const DEFAULT_URL = 'https://git.yoomoney.ru';
    const DEFAULT_BROWSER = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 YaBrowser/17.9.1.768 Yowser/2.5 Safari/537.36';

    const ARCHIVE_BEST = 0;
    const ARCHIVE_ZIP = 1;
    const ARCHIVE_TAR_GZ = 2;

    private $baseUrl;
    private $curl;
    private $browser;

    public function __construct()
    {
        $this->baseUrl = self::DEFAULT_URL;
        $this->browser = self::DEFAULT_BROWSER;
    }

    public function __destruct()
    {
        if ($this->curl !== null) {
            curl_close($this->curl);
            $this->curl = null;
        }
    }

    public function setBrowser($value)
    {
        $this->browser = $value;
        return $this;
    }

    /**
     * Возвращает тег последнего релиза репозитория в битбакете
     *
     * При запросе к битбакету по адресу https://git.yoomoney.ru/pages/<repository>/master/browse/ битбакет возвращает ответ с
     * текущей версией. Для этого в репозитории должен быть файл CHANGELOG.md c этой самой версией.
     *
     * @param string $repository Имя репозитория на битбакете
     *
     * @return string|null Тэг последнего релиза или null если данные получить не удалось
     */
    public function getLatestRelease($repository)
    {
        $curl = $this->getCurl();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->baseUrl . '/projects/' . $repository . '/raw/CHANGELOG.md',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_FOLLOWLOCATION => true,
        ));

        $changelog = curl_exec($curl);
        if (empty($changelog)) {
            return null;
        }
        if (preg_match('/### v([\d\.]+) от/i', $changelog, $match)) {
            return trim($match[1]);
        } else {
            return null;
        }
    }

    /**
     * Метод скачивает файл лога изменений из битбакет репозитория
     *
     * Подразумеваем, что файл лога изменений лежит в корне репозитория. По умолчанию скачивается файл с именем
     * "CHANGELOG.md" из ветки "master".
     *
     * @param string $repository Имя репозитория на битбакете
     * @param string $downloadDir Имя папки в которую будет загружен лог изменений
     * @param string $fileName Имя файла лога изменений
     * @param string $branch Имя ветки в репозитории из которой вытягивается файл изменений
     *
     * @return string|null Полный путь до загруженного файла с логом изменений или null если файл скачать не удалось
     */
    public function downloadLatestChangeLog($repository, $downloadDir, $fileName = 'CHANGELOG.md', $branch = 'master')
    {
        $curl = $this->getCurl();

        $outFileName = rtrim($downloadDir, '/') . '/' . $fileName;
        $file = fopen($outFileName, 'w');
        if (!$file) {
            return null;
        }

        $url = $this->baseUrl . '/projects/' . $repository . '/raw/' . $fileName . '?at='
            . urldecode('refs/heads/' . $branch);
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_FILE => $file,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_FOLLOWLOCATION => true,
        );
        if (defined('CURLOPT_ACCEPT_ENCODING')) {
            $options[CURLOPT_ACCEPT_ENCODING] = true;
        }

        curl_setopt_array($curl, $options);

        $result = curl_exec($curl);
        fclose($file);
        if (empty($result)) {
            return null;
        }
        return $fileName;
    }

    /**
     * Скачивает архив с релизом с битбакета
     *
     * @param string $repository Имя репозитория на битбакете
     * @param string $version Скачиваемая версия релиза
     * @param string $downloadDir Директория в которую скачивается архив с релизом
     * @param int $type Тип архива, одна из констант self::ARCHIVE_XXX
     *
     * @return null|string Полный путь до файла с архивом или null если архив скачать не удалось
     */
    public function downloadRelease($repository, $version, $downloadDir, $type = self::ARCHIVE_BEST)
    {
        $curl = $this->getCurl();

        if ($type === self::ARCHIVE_BEST) {
            if (function_exists('zip_open')) {
                $type = self::ARCHIVE_ZIP;
            } else {
                $type = self::ARCHIVE_TAR_GZ;
            }
        }
        if ($type === self::ARCHIVE_ZIP) {
            $ext = 'zip';
        } elseif ($type === self::ARCHIVE_TAR_GZ) {
            $ext = 'tar.gz';
        } else {
            throw new RuntimeException('Invalid archive type "' . $type . '"');
        }

        $fileName = rtrim($downloadDir, '/') . '/' . $version . '.' . $ext;
        $file = fopen($fileName, 'w');
        if (!$file) {
            return null;
        }

        $url = $this->baseUrl . '/rest/api/latest/projects/' . $repository . '/archive?at='
            . urlencode('refs/tags/' . $version) . '&format=' . $ext;

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_FILE => $file,
            CURLOPT_HEADER => false,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_FOLLOWLOCATION => true,
        ));

        $result = curl_exec($curl);
        fclose($file);
        if (empty($result)) {
            return null;
        }
        return $fileName;
    }

    /**
     * Сравнивает файлы логов изменений, и возвращает строки из нового лога, которых нет в старом
     *
     * @param string $oldChangeLog Имя файла лога старой версии
     * @param string $newChangeLog Имя файла лога новой версии
     *
     * @return null|string Строки из лога в новой версии которых нет в старом логе
     */
    public function diffChangeLog($oldChangeLog, $newChangeLog)
    {
        $old = fopen($oldChangeLog, 'r');
        if (!$old) {
            return null;
        }

        $new = fopen($newChangeLog, 'r');
        if (!$new) {
            fclose($old);
            return null;
        }

        do {
            $stop = trim(fgets($old, 1024));
        } while (empty($stop) && !feof($old));
        fclose($old);

        $result = array();
        while (!feof($new)) {
            $line = trim(fgets($new, 1024));
            if ($line === $stop) {
                break;
            }
            $result[] = $line;
        }
        fclose($new);
        return implode('<br />' . PHP_EOL, $result);
    }

    /**
     * Возвращает инициализированный ресурс курла
     *
     * @return resource Хэндлер курла
     *
     * @throws RuntimeException Выбрасывается если расширение курла не установлено или если хэндлер не удалось создать
     */
    private function getCurl()
    {
        if ($this->curl === null) {
            if (!function_exists('curl_init')) {
                throw new RuntimeException('Curl extension not installed');
            }
            $this->curl = curl_init();
            if (!$this->curl) {
                throw new RuntimeException('Failed to init curl');
            }
        }
        return $this->curl;
    }

}