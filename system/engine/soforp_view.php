<?php

class SoforpWidgets
{

	public $nameSpace = ""; // i.e. 'soforp_backup_'
	public $params = ""; // i.e. array('soforp_backup_status' => 1 );
	public $text_select_all = "";
	public $text_unselect_all = "";

	public function __construct($nameSpace, $params)
	{
		$this->nameSpace = $nameSpace;
		$this->params = $params;
		$this->config_language_id = isset($params['config_language_id']) ? $params['config_language_id'] : 1;
	}

	public function dropdown($property, $options, $params = array())
	{
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="<?php echo (isset($params['disabled']) && $params['disabled'])? 'display:none' : 'display: inline-block; width: 100%;'; ?>">
            <div class="col-sm-5">
            <label class="control-label"
                   for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-7">
            <select name="<?php echo $this->nameSpace . $property; ?>"
                id="<?php echo $this->nameSpace . $property; ?>"
                class="form-control">
                    <?php foreach ($options as $value => $name) { ?>
                        <?php if ($this->params[$this->nameSpace . $property] == $value) { ?>
                        <option value="<?php echo $value; ?>" selected="selected"><?php echo $name; ?></option>
                    <?php } else { ?>
                        <option value="<?php echo $value; ?>"><?php echo $name; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            </div>
        </div>
		<?php
	}

	public function dropdownLite($property, $options, $keys = array())
	{
		?>
        <?php if ($keys) { ?>
        <select class="form-control" name="<?php echo $this->nameSpace . $property . '[' . $keys[0] . ']' . '[' . $keys[1] . ']' . '[' . $keys[2] . ']'; ?>">
            <?php foreach ($options as $value => $name) { ?>
                <?php if ($this->params[$this->nameSpace . $property][ $keys[0]][ $keys[1]][ $keys[2]] == $value || $this->params[$this->nameSpace . $property][ $keys[0]][ $keys[1]][ $keys[2]] === $name) { ?>
                    <option value="<?php echo $value; ?>" selected="selected"><?php echo $name; ?></option>
                <?php } else { ?>
                    <option value="<?php echo $value; ?>"><?php echo $name; ?></option>
                <?php } ?>
            <?php } ?>
        </select>
    <?php } else { ?>
        <select class="form-control" name="<?php echo $this->nameSpace . $property; ?>">
            <?php foreach ($options as $value => $name) { ?>
                <?php if ($this->params[$this->nameSpace . $property] == $value) { ?>
                    <option value="<?php echo $value; ?>" selected="selected"><?php echo $name; ?></option>
                <?php } else { ?>
                    <option value="<?php echo $value; ?>"><?php echo $name; ?></option>
                <?php } ?>
            <?php } ?>
        </select>
	<?php } ?>
		<?php
	}

	public function dropdownLiteTemplate($property, $options, $keys = array())
	{
		?>
		<?php if ($keys) { ?>
        <select class="form-control" name="<?php echo $this->nameSpace . $property . '[' . $keys[0] . ']' . '[' . $keys[1] . ']' . '[' . $keys[2] . ']'; ?>">
			<?php foreach ($options as $value => $name) { ?>
				<?php if ($this->params[$this->nameSpace . $property][ $keys[0]][ $keys[1]][ $keys[2]] === $value || $this->params[$this->nameSpace . $property][ $keys[0]][ $keys[1]][ $keys[2]] === $name) { ?>
                    <option value="<?php echo $name; ?>" selected="selected"><?php echo $name; ?></option>
				<?php } else { ?>
                    <option value="<?php echo $name; ?>"><?php echo $name; ?></option>
				<?php } ?>
			<?php } ?>
        </select>
	<?php } else { ?>
        <select class="form-control" name="<?php echo $this->nameSpace . $property; ?>">
			<?php foreach ($options as $value => $name) { ?>
				<?php if ($this->params[$this->nameSpace . $property] == $value) { ?>
                    <option value="<?php echo $name; ?>" selected="selected"><?php echo $name; ?></option>
				<?php } else { ?>
                    <option value="<?php echo $name; ?>"><?php echo $name; ?></option>
				<?php } ?>
			<?php } ?>
        </select>
	<?php } ?>
		<?php
	}
	/**
	 * <select>
	 *
	 * @param array|string $stores магазины
	 * @param string $property имя параметра
	 * @param array $options значение => текст
	 */
	public function dropdownMultiStore($stores, $property, $options)
	{
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
                <label class="control-label" for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
                <br>
                <?php echo isset($this->params['entry_' . $property . '_desc']) ? $this->params['entry_' . $property . '_desc'] : '' ?>
            </div>

            <div class="col-sm-7">
                <?php foreach ($stores as $store_id => $store) { ?>
                    <select name="<?php echo $this->nameSpace . $property . "[{$store_id}]"; ?>"
                        id="<?php echo $this->nameSpace . $property . "[{$store_id}]"; ?>"
                        class="form-control stores-group store-<?php echo $store_id ?> <?php echo $store_id != 0 ? 'hidden' : '' ?>">
                            <?php foreach ($options as $value => $name) { ?>
                                <?php if ($this->params[$this->nameSpace . $property][$store_id] == $value) { ?>
                                    <option value="<?php echo $value; ?>" selected="selected"><?php echo $name; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $value; ?>"><?php echo $name; ?></option>
                                <?php } ?>
                            <?php } ?>
                    </select>
                <?php } ?>
            </div>
        </div>
		<?php
	}

	public function dropdownA($property, $array, $index, $options)
	{
		$field_id = $array . '-' . $property . '-' . $index;
		?>
        <div class="form-group" id="field_<?php echo $field_id; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
            <label class="control-label"
                   for="<?php echo $this->nameSpace . $field_id; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-7">
            <select name="<?php echo $array . '[' . $index . '][' . $property . ']'; ?>"
                id="<?php echo $this->nameSpace . $field_id; ?>"
                class="form-control">
                    <?php foreach ($options as $value => $name) { ?>
                        <?php if ($this->params[$array][$index][$property] == $value) { ?>
                        <option value="<?php echo $value; ?>" selected="selected"><?php echo $name; ?></option>
                    <?php } else { ?>
                        <option value="<?php echo $value; ?>"><?php echo $name; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            </div>
        </div>
		<?php
	}

	public function dropdownB($property, $array, $options)
	{
		?>
        <label class="control-label"
               for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?>
        </label>
        <div class="panel panel-default" id="<?php echo $this->nameSpace . $property; ?>">
            <div class="panel-body">
                <?php
                foreach ($array as $index => $item) {
                    $field_id = $property . '-' . $index;
                    ?>
                    <div class="form-group" id="field_<?php echo $field_id; ?>" style="display: inline-block; width: 100%;">
                        <div class="col-sm-5">
                            <label class="control-label"
                                   for="<?php echo $this->nameSpace . $field_id; ?>"><?php echo $item; ?></label>
                            <br>
                            <?php
                            if (isset($this->params['entry_' . $property . '_desc']))
                                echo $this->params['entry_' . $property . '_desc'];
                            ?>
                        </div>
                        <div class="col-sm-7">
                            <select name="<?php echo $this->nameSpace . $property . '[' . $index . ']'; ?>"
                                    id="<?php echo $this->nameSpace . $field_id; ?>"
                                    class="form-control">
                                <?php foreach ($options as $value => $name) { ?>
                                    <?php if (isset($this->params[$this->nameSpace . $property][$index]) && $this->params[$this->nameSpace . $property][$index] == $value) { ?>
                                        <option value="<?php echo $value; ?>" selected="selected"><?php echo $name; ?></option>
                                    <?php } else { ?>
                                        <option value="<?php echo $value; ?>"><?php echo $name; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
		<?php
	}

	public function debug_and_logs($property, $options, $clear, $button_clear_log)
	{
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-4">
            <label class="control-label"
                   for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-4">
            <select name="<?php echo $this->nameSpace . $property; ?>"
                id="<?php echo $this->nameSpace . $property; ?>"
                class="form-control">
                    <?php foreach ($options as $value => $name) { ?>
                        <?php if ($this->params[$this->nameSpace . $property] == $value) { ?>
                        <option value="<?php echo $value; ?>" selected="selected"><?php echo $name; ?></option>
                    <?php } else { ?>
                        <option value="<?php echo $value; ?>"><?php echo $name; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            </div>

            <div class="col-sm-4 text-right">
            <button onclick="$('#form').attr('action', '<?php echo $clear; ?>');
                            $('#form').attr('target', '_self');
                            $('#form').submit();" class="btn btn-primary"><span><?php echo $button_clear_log; ?></span></button>
            </div>
        </div>
		<?php
	}

	public function debug_download_logs($property, $options, $clear, $download, $button_clear_log, $button_download_log)
	{
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-4">
            <label class="control-label"
                   for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-4">
            <select name="<?php echo $this->nameSpace . $property; ?>"
                id="<?php echo $this->nameSpace . $property; ?>"
                class="form-control">
                    <?php foreach ($options as $value => $name) { ?>
                        <?php if ($this->params[$this->nameSpace . $property] == $value) { ?>
                        <option value="<?php echo $value; ?>" selected="selected"><?php echo $name; ?></option>
                    <?php } else { ?>
                        <option value="<?php echo $value; ?>"><?php echo $name; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
            </div>
            <div class="pull-right">
            <a href="<?php echo $download; ?>" data-toggle="tooltip" title="" class="btn btn-primary"
               data-original-title="<?php echo $button_download_log; ?>"><i class="fa fa-download"></i></a>
            <a onclick=" $('#form').attr('action', '<?php echo $clear; ?>');
                            $('#form').attr('target', '_self');
                            $('#form').submit()" data-toggle="tooltip" title="" class="btn btn-danger"
               data-original-title="<?php echo $button_clear_log; ?>"><i class="fa fa-eraser"></i></a>
            </div>
        </div>
		<?php
	}

	public function input($property)
	{
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
            <label class="control-label"
                   for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-7">
            <input name="<?php echo $this->nameSpace . $property; ?>"
                   id="<?php echo $this->nameSpace . $property; ?>" class="form-control"
                   value="<?php echo isset($this->params[$this->nameSpace . $property]) ? $this->params[$this->nameSpace . $property] : ''; ?>"/>
            </div>
        </div>
		<?php
	}

	public function inputRequired($property, $required = false, $error = '')
	{
		?>
        <div class="form-group<?php echo $required ? ' required' : ''; ?>" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
                <label class="control-label"
                       for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
                <br>
                <?php
                if (isset($this->params['entry_' . $property . '_desc']))
                    echo $this->params['entry_' . $property . '_desc'];
                ?>
            </div>
            <div class="col-sm-7">
                <input name="<?php echo $this->nameSpace . $property; ?>"
                       id="<?php echo $this->nameSpace . $property; ?>" class="form-control"
                       value="<?php echo isset($this->params[$this->nameSpace . $property]) ? $this->params[$this->nameSpace . $property] : ''; ?>"/>
            </div>
            <?php if (!empty($error)) { ?>
                <div class="text-danger col-sm-7 col-sm-offset-5"><?php echo $error; ?></div>
            <?php } ?>
        </div>
		<?php
	}

	public function dateInput($property, $required = false, $error = '')
	{
		?>
        <div class="form-group <?php echo $required ? 'required' : ''; ?> " id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
                <label class="control-label" for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            </div>
            <div class="col-sm-7">
                <div class="input-group date">
                    <input type="text" data-date-format="YYYY-MM-DD" class="form-control"
                           name="<?php echo $this->nameSpace . $property; ?>"
                           id="<?php echo $this->nameSpace . $property; ?>"
                           value="<?php echo isset($this->params[$this->nameSpace . $property]) ? $this->params[$this->nameSpace . $property] : ''; ?>"
                           placeholder="<?php echo $this->params['entry_' . $property]; ?>"
                    />
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
                </div>
            </div>
            <?php if (!empty($error)) { ?>
                <div class="text-danger col-sm-7 col-sm-offset-5"><?php echo $error; ?></div>
            <?php } ?>
        </div>
		<?php
	}

	public function inputLite($property, $keys = array())
	{
		?>
        <?php if ($keys) { ?>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-cogs" aria-hidden="true" style="width:16px"></i></span>
            <input name="<?php echo $this->nameSpace . $property . '[' . $keys[0] . ']' . '[' . $keys[1] . ']' . '[' . $keys[2] . ']'; ?>"
                   id="<?php echo $this->nameSpace . $property . '_' . $keys[0] . '_' . $keys[1]; ?>"
                   class="form-control"
                   value="<?php echo $this->params[$this->nameSpace . $property][$keys[0]][$keys[1]][ $keys[2]]; ?>"/>
        </div>
    <?php } else { ?>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-cogs" aria-hidden="true" style="width:16px"></i></span>
            <input name="<?php echo $this->nameSpace . $property; ?>"
                   id="<?php echo $this->nameSpace . $property; ?>"
                   class="form-control"
                   value="<?php echo $this->params[$this->nameSpace . $property]; ?>"/>
        </div>
    <?php } ?>
		<?php
	}

	/**
	 * <input>
	 *
	 * @param array|string $stores магазины
	 * @param string $property имя параметра
	 */
	public function inputMultiStore($stores, $property)
	{
		?>
        <div class="form-group" id="field_<?php echo $property . '[0]'; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
                <label class="control-label" for="<?php echo $this->nameSpace . $property . '[0]'; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
                <br>
                <?php echo isset($this->params['entry_' . $property . '_desc']) ? $this->params['entry_' . $property . '_desc'] : '' ?>
            </div>
            <div class="col-sm-7">
                <?php foreach ($stores as $store_id => $store) { ?>
                    <div class="input-group stores-group store-<?php echo $store_id ?> <?php echo $store_id != 0 ? 'hidden' : '' ?>">
                        <input name="<?php echo $this->nameSpace . $property . "[{$store_id}]"; ?>"
                               id="<?php echo $this->nameSpace . $property . "[{$store_id}]"; ?>"
                               class="form-control"
                               value="<?php echo (array_key_exists($store_id, $this->params[$this->nameSpace . $property])) ? $this->params[$this->nameSpace . $property][$store_id] : ''; ?>"/>
                    </div>
                <?php } ?>
            </div>
        </div>
		<?php
	}

	public function inputColor($property)
		{
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
                <label class="control-label"
                        for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
                <br>
                <?php
                if (isset($this->params['entry_' . $property . '_desc']))
                    echo $this->params['entry_' . $property . '_desc'];
                ?>
            </div>
            <div class="col-sm-7">
                <div class="input-group colorpicker-component">
                    <input name="<?php echo $this->nameSpace . $property; ?>"
                            id="<?php echo $this->nameSpace . $property; ?>"
                            value="<?php echo $this->params[$this->nameSpace . $property]; ?>"
                            class="form-control"/>
                    <span class="input-group-addon"><i></i></span>
                </div>
            </div>
        </div>
		<?php
	}

	public function inputGradientColor($property)
	{
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
                <label class="control-label"
                       for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
                <br>
                <?php
                if (isset($this->params['entry_' . $property . '_desc']))
                    echo $this->params['entry_' . $property . '_desc'];
                ?>
            </div>
            <div class="col-sm-7">
                <div class="input-group gradient-group">
                    <input name="<?php echo $this->nameSpace . $property; ?>"
                           id="<?php echo $this->nameSpace . $property; ?>"
                           value="<?php echo $this->params[$this->nameSpace . $property]; ?>"
                           class="form-control gradient-component"/>
                </div>
            </div>
        </div>
		<script>
			$('#<?php echo $this->nameSpace . $property; ?>').coloringPick();
		</script>
		<?php
	}

	public function inputImage($property, $placeholder, $img)
	{
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
            <label class="control-label" for="<?php echo $this->nameSpace . $property; ?>">
                <?php echo $this->params['entry_' . $property]; ?>
            </label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-xs-7">
            <a href="" id="thumb-image-<?php echo $property ?>" data-toggle="image" class="img-thumbnail">
                <img src="<?php echo $img; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>"/></a>
            <input type="hidden" name="<?php echo $this->nameSpace . $property; ?>"
                   value="<?php echo $this->params[$this->nameSpace . $property]; ?>"
                   id="input-image-<?php echo $property ?>"/>
            </div>
        </div>
		<?php
	}

	public function inputA($property, $array, $index)
	{ // Arrays
		$field_id = $array . '-' . $property . '-' . $index;
		?>
        <div class="form-group" id="field_<?php echo $field_id; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
            <label class="control-label"
                   for="<?php echo $this->nameSpace . $field_id; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-7">
            <input name="<?php echo $array . '[' . $index . '][' . $property . ']'; ?>"
                   id="<?php echo $field_id; ?>" class="form-control"
                   value="<?php echo $this->params[$array][$index][$property]; ?>"/>
            </div>
        </div>
		<?php
	}

	public function localeInput($property, $languages)
	{
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
            <label class="control-label"
                   for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-7">
            <?php foreach ($languages as $language) { ?>
                <div class="input-group">
                    <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>"
                                     title="<?php echo $language['name']; ?>"/></span>
                    <input
                    name="<?php echo $this->nameSpace . $property; ?>[<?php echo $language['language_id']; ?>]"
                    id="<?php echo $this->nameSpace . $property . $language['language_id']; ?>"
                    class="form-control"
                    value="<?php if (isset($this->params[$this->nameSpace . $property][$language['language_id']]))  echo $this->params[$this->nameSpace . $property][$language['language_id']]; else echo ''; ?>"/>
                </div>
            <?php } ?>
            </div>
        </div>
		<?php
	}

	public function localeInputRequired($property, $languages, $required = false, $error = '')
	{
		?>
        <div class="form-group<?php echo $required ? ' required' : ''; ?>" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
                <label class="control-label"
                       for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
                <br>
                <?php
                if (isset($this->params['entry_' . $property . '_desc']))
                    echo $this->params['entry_' . $property . '_desc'];
                ?>
            </div>
            <div class="col-sm-7">
                <?php foreach ($languages as $language) { ?>
                    <div class="input-group">
                    <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>"
                                                         title="<?php echo $language['name']; ?>"/></span>
                        <input
                            name="<?php echo $this->nameSpace . $property; ?>[<?php echo $language['language_id']; ?>]"
                            id="<?php echo $this->nameSpace . $property . $language['language_id']; ?>"
                            class="form-control"
                            value="<?php if (isset($this->params[$this->nameSpace . $property][$language['language_id']]))  echo $this->params[$this->nameSpace . $property][$language['language_id']]; else echo ''; ?>"/>
                    </div>
                <?php } ?>
            </div>
            <?php if (!empty($error)) { ?>
                <div class="text-danger col-sm-7 col-sm-offset-5"><?php echo $error; ?></div>
            <?php } ?>
        </div>
		<?php
	}

	public function localeInputLite($property, $languages, $keys = array())
	{
		?>
        <?php if ($keys) { ?>
        <?php foreach ($languages as $language) { ?>
            <div class="input-group">
                <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/></span>
                <input name="<?php echo $this->nameSpace . $property . '[' . $keys[0] . ']' . '[' .$language['language_id'] . ']' . '[' . $keys[1] . ']'; ?>"
                       id="<?php echo $this->nameSpace . $property . '_' . $keys[0] . '_' . $language['language_id']; ?>"
                       class="form-control"
                       value="<?php echo $this->params[$this->nameSpace . $property][ $keys[0]][ $language['language_id']][ $keys[1]] ?>"/>
            </div>
        <?php } ?>
    <?php } else { ?>
        <?php foreach ($languages as $language) { ?>
            <div class="input-group">
                <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/></span>
                <input name="<?php echo $this->nameSpace . $property; ?>[<?php echo $language['language_id']; ?>]"
                       id="<?php echo $this->nameSpace . $property . $language['language_id']; ?>"
                       class="form-control"
                       value="<?php if (isset($this->params[$this->nameSpace . $property][$language['language_id']]))  echo $this->params[$this->nameSpace . $property][$language['language_id']]; else echo ''; ?>"/>
            </div>
        <?php } ?>
    <?php } ?>
		<?php
	}

	/**
	 * <input>
	 *
	 * @param array|string $stores магазины
	 * @param string $property имя параметра
	 * @param array $languages языки
	 */
	public function localeInputMultiStore($stores, $property, $languages)
	{
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
                <label class="control-label" for="<?php echo $this->nameSpace . $property . '[0]'; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
                <br>
                <?php echo isset($this->params['entry_' . $property . '_desc']) ? $this->params['entry_' . $property . '_desc'] : '' ?>
            </div>
            <div class="col-sm-7">
                <?php foreach ($stores as $store_id => $store) { ?>
                    <?php foreach ($languages as $language) { ?>
                        <div class="input-group stores-group store-<?php echo $store_id ?> <?php echo $store_id != 0 ? 'hidden' : '' ?>">
                            <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/></span>
                            <input
                                name="<?php echo $this->nameSpace . $property . "[{$store_id}]" . '[' . $language['language_id'] . ']'; ?>"
                                id="<?php echo $this->nameSpace . $property . "[{$store_id}]" . '[' . $language['language_id'] . ']'; ?>"
                                class="form-control"
                                value="<?php echo $this->params[$this->nameSpace . $property][$store_id][$language['language_id']]; ?>"/>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
		<?php
	}

	public function localeTextarea($property, $languages)
	{
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
            <label class="control-label"
                   for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-7">
            <ul class="nav nav-tabs">
                <?php foreach ($languages as $language) { ?>
                    <li class="<?php echo $language['language_id'] == $this->config_language_id ? 'active' : ''; ?>"><a
                        href="#column-<?php echo $this->nameSpace; ?><?php echo $property; ?>_<?php echo $language['language_id']; ?>"
                        data-toggle="tab">
                        <img src="view/image/flags/<?php echo $language['image']; ?>"
                         title="<?php echo $language['name']; ?>">
                         <?php echo $language['name']; ?>
                    </a>
                    </li>
                <?php } ?>
            </ul>
            <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                    <div class="tab-pane <?php echo $language['language_id'] == $this->config_language_id ? 'active' : ''; ?>"
                     id="column-<?php echo $this->nameSpace; ?><?php echo $property; ?>_<?php echo $language['language_id']; ?>">
                    <textarea rows="6"
                          name="<?php echo $this->nameSpace . $property; ?>[<?php echo $language['language_id']; ?>]"
                          id="<?php echo $this->nameSpace . $property . $language['language_id']; ?>"
                          class="form-control"><?php if (!empty($this->params[$this->nameSpace . $property][$language['language_id']])){ echo $this->params[$this->nameSpace . $property][$language['language_id']];}else{ echo ''; } ?></textarea>
                    </div>
                <?php } ?>
            </div>
            </div>
        </div>
		<?php
	}

	function password($property)
	{
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
            <label class="control-label"
                   for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-7">
            <input type="password" name="<?php echo $this->nameSpace . $property; ?>"
                   id="<?php echo $this->nameSpace . $property; ?>" class="form-control"
                   value="<?php echo $this->params[$this->nameSpace . $property]; ?>"/>
            </div>
        </div>
		<?php
	}

	public function textarea($property, $rows = 6)
	{
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
            <label class="control-label"
                   for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-7">
            <textarea name="<?php echo $this->nameSpace . $property; ?>"
                  id="<?php echo $this->nameSpace . $property; ?>"
                  rows="<?php echo $rows; ?>"
                  class="form-control"><?php echo $this->params[$this->nameSpace . $property]; ?></textarea>
            </div>
        </div>
		<?php
	}

	public function textareaMultiStore($stores, $property, $rows = 6, $style = "")
	{
		$style = !empty($style) ? "style='" . $style . "'" : "";
		$rows = $style ? '' : 'rows="' . $rows . '"';
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
                <label class="control-label" for="<?php echo $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
                <br>
                <?php
                if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
                ?>
            </div>
            <div class="col-sm-7">
                <?php foreach ($stores as $store_id => $store) { ?>
                <div class="stores-group store-<?php echo $store_id ?> <?php echo $store_id != 0 ? 'hidden' : '' ?>">
                    <textarea name="<?php echo $this->nameSpace . $property . "[{$store_id}]"; ?>"
                        id="<?php echo $this->nameSpace . $property . "[{$store_id}]"; ?>"
                        <?php echo $rows; ?>
                        class="form-control" <?php echo $style; ?> ><?php echo $this->params[$this->nameSpace . $property][$store_id]; ?>
                    </textarea>
                </div>
            <?php } ?>
        </div>
    </div>
	<?php
	}

	public function textareaWideMultiStore($stores, $property, $rows = 6, $style = "")
	{
		$style = !empty($style) ? "style='" . $style . "'" : "";
		$rows = $style ? '' : ('rows="' . $rows . '"');
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-12">
                <?php foreach ($stores as $store_id => $store) { ?>
                <div class="stores-group store-<?php echo $store_id ?> <?php echo $store_id != 0 ? 'hidden' : '' ?>">
                    <textarea name="<?php echo $this->nameSpace . $property . "[{$store_id}]"; ?>"
                        id="<?php echo $this->nameSpace . $property . "[{$store_id}]"; ?>"
                        <?php echo $rows; ?>
                        class="form-control" <?php echo $style; ?> ><?php echo $this->params[$this->nameSpace . $property][$store_id]; ?>
                    </textarea>
                </div>
                <?php } ?>
            </div>
        </div>
		<?php
	}

	public function textareaA($property, $array, $index, $rows = 6)
	{
		$field_id = $array . '-' . $property . '-' . $index;
		?>
        <div class="form-group <?php echo $property; ?>" id="field_<?php echo $field_id; ?>"
             style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
            <label class="control-label"
                   for="<?php echo $field_id; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-7">
            <textarea name="<?php echo $array . '[' . $index . '][' . $property . ']'; ?>"
                  id="<?php echo $field_id; ?>"
                  rows="<?php echo $rows; ?>"
                  class="form-control"><?php echo $this->params[$array][$index][$property]; ?></textarea>
            </div>
        </div>
		<?php
	}

	public function localeTextareaA($property, $array, $index, $languages, $rows = 6)
	{
		$field_id = $array . '-' . $property . '-' . $index;
		?>
        <div class="form-group <?php echo $property; ?>" id="field_<?php echo $field_id; ?>"
             style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
            <label class="control-label"
                   for="<?php echo $field_id; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-7">
            <ul class="nav nav-tabs" id="languages">
                <?php foreach ($languages as $language) { ?>
                    <li class="<?php echo $language['language_id'] == $this->config_language_id ? 'active' : ''; ?>"><a
                        href="#column-<?php echo $field_id ?>-<?php echo $language['language_id']; ?>"
                        data-toggle="tab" >
                        <img src="view/image/flags/<?php echo $language['image']; ?>"
                         title="<?php echo $language['name']; ?>">
                         <?php echo $language['name']; ?>
                    </a>
                    </li>
                <?php } ?>
            </ul>
            <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                    <div class="tab-pane <?php echo $language['language_id'] == $this->config_language_id ? 'active' : ''; ?>"
                     id="column-<?php echo $field_id . '-' . $language['language_id']; ?>">
                    <textarea name="<?php echo $array . '[' . $index . '][' . $property . '][' . $language['language_id'] . ']'; ?>"
                          id="<?php echo $field_id . '_' . $language['language_id']; ?>"
                          rows="<?php echo $rows; ?>"
                          class="form-control"><?php echo $this->params[$array][$index][$property][$language['language_id']]; ?></textarea>
                    </div>
                <?php } ?>
            </div>
            </div>
        </div>
		<?php
	}

	public function text($property)
	{
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
            <label class="control-label"
                   for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-7">
            <?php echo $this->params[$this->nameSpace . $property]; ?>
            </div>
        </div>
		<?php
	}

	public function textA($property, $array, $index)
	{
		$field_id = $array . '-' . $property . '-' . $index;
		?>
        <div class="form-group <?php echo $property; ?>" id="field_<?php echo $field_id; ?>"
             style="display: inline-block; width: 100%;">
            <div class="col-sm-5">
            <label class="control-label"
                   for="<?php echo $this->nameSpace . $field_id; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-7">
            <?php echo $this->params[$array][$index][$property]; ?>
            </div>
        </div>
		<?php
	}

	public function checklist($property, $options)
	{
		if (!is_array($this->params[$this->nameSpace . $property])) {
			$this->params[$this->nameSpace . $property] = array();
		}
		?>
        <style>.well label {font-weight: normal; margin-bottom: 0;}</style>
        <div class="form-group store" id="field_<?php echo $property; ?>" style="display: inline-block; width:100%">
            <div class="col-sm-5">
            <label class="control-label"
                   for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-7">
            <div class="well well-sm" style="height: 150px; overflow: auto;">
                <?php $class = 'odd'; ?>
                <?php foreach ($options as $value => $name) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div class="<?php echo $class; ?>">
                        <label>
                    <input type="checkbox" name="<?php echo $this->nameSpace . $property; ?>[]"
                           value="<?php echo $value; ?>"<?php
                           if (in_array($value, $this->params[$this->nameSpace . $property]))
                               echo ' checked="checked"';
                           ?> />
                           <?php echo $name; ?>
                        </label>
                    </div>
                <?php } ?>
            </div>
            <button type="button" onclick="$(this).parent().find(':checkbox').prop('checked', true);"
                class="btn btn-primary"><i class="fa fa-pencil"></i> <?php echo $this->text_select_all; ?></button>
            <button type="button" onclick="$(this).parent().find(':checkbox').prop('checked', false);"
                class="btn btn-danger"><i class="fa fa-trash-o"></i> <?php echo $this->text_unselect_all; ?>
            </button>
            </div>
        </div>
		<?php
	}

	public function checklistA($property, $array, $index, $options)
	{
		$field_id = $array . '-' . $property . '-' . $index;
		?>
        <div class="form-group store" id="field_<?php echo $field_id; ?>" style="display: inline-block; width:100%">
            <div class="col-sm-5">
            <label class="control-label"
                   for="<?php echo $this->nameSpace . $field_id; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-7">
            <div class="well well-sm" style="height: 150px; overflow: auto;">
                <?php $class = 'odd'; ?>
                <?php foreach ($options as $value => $name) { ?>
                    <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                    <div class="<?php echo $class; ?>">
                    <input type="checkbox"
                           name="<?php echo $array . '[' . $index . '][' . $property . '][]'; ?>"
                           value="<?php echo $value; ?>"
                           <?php
                           if (in_array($value, $this->params[$array][$index][$property]))
                               echo ' checked="checked"';
                           ?> />
                           <?php echo $name; ?>
                    </div>
                <?php } ?>
            </div>
            <button type="button" onclick="$(this).parent().find(':checkbox').prop('checked', true);"
                class="btn btn-primary"><i class="fa fa-pencil"></i> <?php echo $this->text_select_all; ?></button>
            <button type="button" onclick="$(this).parent().find(':checkbox').prop('checked', false);"
                class="btn btn-danger"><i class="fa fa-trash-o"></i> <?php echo $this->text_unselect_all; ?>
            </button>
            </div>
        </div>
		<?php
	}

	public function listAutocomplete($property, $options)
	{
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width:100%">
            <div class="col-sm-5">
                <label class="control-label"
                       for="input-<?php echo $property; ?>">
                    <span data-toggle="tooltip" title="<?php echo $this->params['help_' . $property]; ?>">
                    <?php echo $this->params['entry_' . $property]; ?></span>
                </label>
            </div>
            <div class="col-sm-7">
                <input type="text" name="input-<?php echo $property; ?>" value="" placeholder="<?php echo $this->params['entry_' . $property]; ?>" id="input-<?php echo $property; ?>" class="form-control" />
                <div id="list-<?php echo $property; ?>" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($options as $option ) { ?>
                        <div id="<?php echo $property; ?><?php echo $option['id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $option['name']; ?>
                            <input type="hidden" name="auto-<?php echo $property; ?>[]" value="<?php echo $option['id']; ?>" />
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
		<?php
	}
	
	/**
	 * <input>
	 *
	 * @param array|string $stores магазины
	 * @param string $property имя параметра
	 * @param array $options  массив
	 */
	public function checklistMultiStore($stores, $property, $options)
	{
		?>
        <div class="form-group store" id="field_<?php echo $property; ?>" style="display: inline-block; width:100%">
            <div class="col-sm-5">
                <label class="control-label" for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
                <br>
                <?php
                if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
                ?>
            </div>
            <div class="col-sm-7">
                <?php foreach ($stores as $store_id => $store) { ?>
                <div class="stores-group store-<?php echo $store_id ?> <?php echo $store_id != 0 ? 'hidden' : '' ?>">
                    <div class="well well-sm" style="height: 150px; overflow: auto;">
                        <?php $class = 'odd'; ?>
                        <?php foreach ($options as $value => $name) { ?>
                        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                        <div class="<?php echo $class; ?>">
                            <input type="checkbox" name="<?php echo $this->nameSpace . $property . "[{$store_id}]"; ?>[]"
                                value="<?php echo $value; ?>"<?php
                                if (array_key_exists($store_id, $this->params[$this->nameSpace . $property]) && in_array($value, $this->params[$this->nameSpace . $property][$store_id]))
                                echo ' checked="checked"';
                                ?> />
                                <?php echo $name; ?>
                        </div>
                        <?php } ?>
                    </div>
                    <button type="button" onclick="$(this).parent().find(':checkbox').prop('checked', true);"
                        class="btn btn-primary"><i class="fa fa-pencil"></i> <?php echo $this->text_select_all; ?>
                    </button>
                    <button type="button" onclick="$(this).parent().find(':checkbox').prop('checked', false);"
                        class="btn btn-danger"><i class="fa fa-trash-o"></i> <?php echo $this->text_unselect_all; ?>
                    </button>
                </div>
                <?php } ?>
            </div>
        </div>
		<?php
	}

	public function checkbox($property)
	{
		?>
        <div class="form-group store" id="field_<?php echo $property; ?>" style="display: inline-block; width:100%">
            <div class="col-sm-5">
            <label class="control-label"
                   for="<?php echo $this->nameSpace . $property; ?>"><?php echo $this->params['entry_' . $property]; ?></label>
            <br>
            <?php
            if (isset($this->params['entry_' . $property . '_desc']))
                echo $this->params['entry_' . $property . '_desc'];
            ?>
            </div>
            <div class="col-sm-7">
            <input type="checkbox" name="<?php echo $this->nameSpace . $property; ?>"
                   value="<?php echo $this->params[$this->nameSpace . $property]; ?>"<?php
                   if ($this->params[$this->nameSpace . $property])
                       echo ' checked="checked"';
                   ?> />
            </div>
        </div>
		<?php
	}

	public function button($action,$name,$class='btn btn-default')
	{
		?>
        <div class="form-group" style="display: inline-block; width: 100%;">
            <div class="col-sm-12">
                <a class="<?php echo $class; ?>" href="<?php echo $action; ?>"><?php echo $name; ?></a>
            </div>
        </div>
	<?php
	}

	/**
	 * выводит кнопку выбора магазина в настройках модуля
	 *
	 * @param array $stores магазины
	 */
	public function storesDropdown($stores)
	{
		?>
        <select id="select-store-dropdown" class="form-control pull-left">
            <?php foreach($stores as $store_id => $store_data) { ?>
                <option value="<?php echo $store_id; ?>"><?php echo $store_data['name']; ?></option>
            <?php } ?>
        </select>

		<script type="text/javascript">
			$(document).ready(function() {
				$('body').on('change', '#select-store-dropdown', function() {
					var store_id = $(this).val();

					$('.stores-group').each(function() {
						if ($(this).hasClass('store-' + store_id))
							$(this).removeClass('hidden');
						else
							$(this).addClass('hidden');
					});

				});
			});
		</script>
		<?php
	}

	public function progressBar($property, $callback, $token = false)
	{
		?>
        <div class="form-group" id="field_<?php echo $property; ?>" style="display: inline-block; width: 100%;">
            <div class="col-sm-12">
                <a onclick="build<?php echo ucwords($this->nameSpace) . ucwords($property); ?>($(this), 0)" class="btn btn-primary buttonProgress"><span><?php echo $this->params['entry_' . $property]; ?></span></a>
                <div id="progressBar<?php echo ucwords($this->nameSpace) . ucwords($property); ?>" class="hide progressBar">
                    <span class="msgConfig"><?php echo 'text_calculating' ?></span>
                    <div></div>
                </div>
                <img class="loader" id="loader<?php echo ucwords($this->nameSpace) . ucwords($property); ?>" src="view/image/loading.gif" style="display: none" />
            </div>
        </div>
		<script type="text/javascript">
			function build<?php echo ucwords($this->nameSpace) . ucwords($property); ?>(el, offset) {
				var onClickAttr = el.attr('onclick');
				el.attr('onclick','').unbind('click');
				el.addClass('disabled');

				$('#progressBar<?php echo ucwords($this->nameSpace) . ucwords($property); ?>').removeClass('hide').show();
				$('#loader<?php echo ucwords($this->nameSpace) . ucwords($property); ?>').show();
				$('.prError').remove();

				$.ajax({
					type:'POST',
					url: 'index.php?route=<?php echo $callback; ?>&offset='+offset+'&token=<?php echo $token; ?>',
					dataType: 'json',
					success: function(data) {

						if(data['error']) {
							$('#progressBar<?php echo ucwords($this->nameSpace) . ucwords($property); ?>').addClass('hide');
							$('#progressBar<?php echo ucwords($this->nameSpace) . ucwords($property); ?>').after("<span class='generating-error alert alert-warning'>"+data['error']+"</span>");
							$('#loader<?php echo ucwords($this->nameSpace) . ucwords($property); ?>').hide();
							el.attr('onclick',onClickAttr).bind('click');
							el.removeClass('disabled');
							return false;
						}

						var percent = Math.floor((data['offset']/data['total']) * 100);

						$('#progressBar<?php echo ucwords($this->nameSpace) . ucwords($property); ?>').find('div').animate({ width: percent + '%' }, 200).html(percent + "%&nbsp;");

						if(percent >= 100) {
							$('#progressBar<?php echo ucwords($this->nameSpace) . ucwords($property); ?>').removeClass('hide').show();
							$('#progressBar<?php echo ucwords($this->nameSpace) . ucwords($property); ?>').find('div').text('Done');
							$('#loader<?php echo ucwords($this->nameSpace) . ucwords($property); ?>').hide();
							el.attr('onclick',onClickAttr).bind('click');
							el.removeClass('disabled');
						} else {
							build<?php echo ucwords($this->nameSpace) . ucwords($property); ?>(el, data['offset']);
						}
					}
				});

			}
		</script>
		<?php
	}
	
	public function usefullLinks()
	{
		?>
        <div class="form-group" style="display: inline-block; width: 100%;">
            <div class="col-sm-12">
                <ul>
                    <?php if (isset($this->params['instruction_link'])) { ?>
                    <li><b><?php echo $this->params['entry_instruction']; ?></b> <?php echo $this->params['instruction_link']; ?></li>
                    <?php } ?>
                    <?php if (isset($this->params['history_link'])) { ?>
                    <li><b><?php echo $this->params['entry_history']; ?></b> <?php echo $this->params['history_link']; ?></li>
                    <?php } ?>
                    <?php if (isset($this->params['faq_link'])) { ?>
                    <li><b><?php echo $this->params['entry_faq']; ?></b> <?php echo $this->params['faq_link']; ?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
		<?php
	}
}
