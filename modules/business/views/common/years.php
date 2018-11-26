<select class="<?= $class ?>" id="<?= $id ?>">
    <?php foreach (range($startYear, $endYear) as $year) { ?>
        <option <?= $year == $currentYear ? 'selected="selected"' : '' ?> value="<?= $year ?>"><?= $year ?></option>
    <?php } ?>
</select>