<div class="prmn-cmngr__confirm">
    <?= $text_your_city; ?> &mdash; <span class="prmn-cmngr__confirm-city"><?= $city ?></span>?
    <div class="prmn-cmngr__confirm-btns">
        <input class="prmn-cmngr__confirm-btn btn btn-primary" value="<?= $text_yes; ?>" type="button" data-value="yes"
               data-redirect="<?= $confirm_redirect ?>">
        <input class="prmn-cmngr__confirm-btn btn" value="<?= $text_no; ?>" type="button" data-value="no">
    </div>
</div>