<div class="filter-buttons d-flex w-100 row ml-5">
    <input type="hidden" name="kode" id="kode" value="<?= $kode; ?>">
    <div class="col-md-2">
        <label for="">Filter Option</label>
        <select id="filter-search" class="btn mb-3 ml-2 w-100 btn-light">
            <?php foreach($filter_search as $key => $value): ?>
                <option value="<?= $value->id; ?>#<?= $value->filter_value; ?>#<?= $value->filter_type; ?>"><?= $value->filter_name; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="col-md-2">
        <label for="">Filter Value</label>
        <select id="filter-select-option" class="btn mb-3 ml-2 w-100 btn-light">
            <option value="0">Filter Value</option>
        </select>
        <input type="text" id="txt-filter-search" class="hidden btn">
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button type="button" class="btn btn-primary mb-3 ml-2" id="filter-button">Search</button>
        <button type="button" class="btn btn-primary mb-3 ml-2" id="reset-button">Reset</button>
    </div>
    <div class="col-md-2 d-flex col-md-6 d-flex justify-content-end">
        <button type="button" class="btn btn-warning mb-3 tambah-bug" data-toggle="modal" data-target="#mediumModal">
            Tambah Bug
        </button>
    </div>
</div>
    