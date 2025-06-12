<style>
    .text-danger {
        color: #ff0000; /* Red color */
    }

    .icon-large {
        font-size: 1.1em;
    }
    .tambah-bug {
        margin-left: auto;
    }
    .filter-buttons select, .filter-buttons button {
        height: calc(1.5em + .75rem + 2px); /* Menyesuaikan dengan tinggi select */
    }

    .filter-buttons .col-md-2.d-flex {
        align-items: end;
    }
</style>
<!-- MAIN CONTENT-->
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="overview-wrap">
                        <h2 class="title-1"><?= $title ?></h2>
                    </div>
                </div>
            </div>
            <link href="path/to/lightbox.css" rel="stylesheet" />
            <div class="row m-t-25">
                <!-- <div class="col-md-12">
                    <button type="button" class="btn btn-primary mb-3 tambah-bug" data-toggle="modal" data-target="#mediumModal">
                        Tambah Bug
                    </button>
                </div> -->
                <?php $this -> load -> view('include/filter_search'); ?>
                <div class="table-responsive table--no-card m-b-30 mt-3">
                    <table class="table table-borderless table-data3">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>Tanggal</th>
                                <th>Modul</th>
                                <th>Message</th>
                                <th>Test Step</th>
                                <th>Screenshoot</th>
                                <th>PIC</th>
                                <th>Status</th>
                                <th>QA Note</th>
                                <th>Dev Note</th>
                                <th>Severity</th>
                            </tr>
                        </thead>
                        <tbody class="dt-grid">
                            <?php if (!empty($buglist)): ?>
                            <?php $i = 1; ?>
                            <?php foreach ($buglist as $lbu) : ?>
                                <tr>
                                    <td><?= $i; ?></td>
                                    <td>
                                        <a href="<?= base_url(); ?>fitur/edit_buglist/<?= $lbu->id ?>" class="text-success">
                                            <i class="fa fa-edit icon-large"></i>
                                        </a>
                                        <a href="<?= base_url(); ?>fitur/hapus_buglist/<?= $lbu->id ?>" class="text-danger tombol-hapus">
                                            <i class="fa fa-trash icon-large"></i>
                                        </a>
                                        <button type="button" class="text-info tombol-view" data-toggle="modal" data-target="#viewModal<?=$lbu->id?>">
                                            <i class="fa fa-eye icon-large"></i>
                                        </button>
                                    </td>
                                    <td><?= date('Y-m-d h:i A', strtotime($lbu->tanggal)) ?></td>
                                    <td><?= $lbu->modul ?></td>
                                    <td><?= $lbu->test_case ?></td>
                                    <td><?= $lbu->test_step ?></td>
                                    <td>
                                        <img src="<?= base_url('assets/lampiran/' . $lbu->screenshoot) ?>" alt="Uploaded Image" class="img-thumbnail" style="max-width:200px; max-height:200px;" data-toggle="modal" data-target="#imageModal" onclick="showImageModal('<?= base_url('assets/lampiran/' . $lbu->screenshoot) ?>')">
                                    </td>
                                    <td>
                                        <?php
                                            $pic = $lbu->user_fullname;
                                            $picClass = isset($developerBadgeClasses[$pic]) ? $developerBadgeClasses[$pic] : 'badge badge-pill badge-primary';
                                        ?>
                                        <span class="<?= $picClass; ?>"><?= $pic; ?></span>
                                    </td>
                                    <td>
                                        <?php
                                            $statusClass = '';
                                            $statusLabel = '';
                                            switch ($lbu->status) {
                                                case '1':
                                                    $statusClass = 'badge badge-pill badge-danger';
                                                    $statusLabel = "Open";
                                                    break;
                                                case '2':
                                                    $statusClass = 'badge badge-pill badge-warning';
                                                    $statusLabel = "Ready To Test";
                                                    break;
                                                case '3':
                                                    $statusClass = 'badge badge-pill badge-success';
                                                    $statusLabel = "Close";
                                                    break;
                                                default:
                                            }
                                        ?>
                                        <span class="<?= $statusClass; ?>"><?= $lbu->status_desc; ?></span>
                                    </td>
                                    <td><?= $lbu->qa_note ?></td>
                                    <td><?= $lbu->dev_note ?></td>
                                    <td><?= $lbu->severity_desc ?></td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="12">No bug list available</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (validation_errors()) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= validation_errors(); ?>
                    </div>
                <?php endif; ?>

                <?= $this->session->flashdata('message'); ?>
                <div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"></div>
                <?php if ($this->session->flashdata('flash')) : ?>
                <?php endif; ?>
                <script>
                    document.getElementById('attachment').addEventListener('change', function() {
                        var selectedFile = this.files[0];
                        console.log('File yang diunggah:', selectedFile);
                    });
                </script>
            </div>
        </div>
    </div>
</div>

<!-- modal medium -->
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newRoleModalLabel">Tambah Bug List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open_multipart('fitur/add_buglist', 'id="addLogBookForm"'); ?> 
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" class="form-control" id="kode" name="kode" value="<?= $user['kode'] ?>">
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="datetime-local" class="form-control" id="tgl" name="tgl">
                </div>
                <div class="form-group">
                <label for="Modul">Modul</label>
                    <textarea name="modul" id="modul" rows="3" placeholder="Tulis Modul" class="form-control"></textarea>
                </div>
                <div class="form-group">
                <label for="Test Case">Test Case</label>
                    <textarea name="test_case" id="test_case" rows="4" placeholder="Tulis Test Case" class="form-control"></textarea>
                </div>
                <div class="form-group">
                <label for="Test Step">Test Step</label>
                    <textarea name="test_step" id="test_step" rows="5" placeholder="Tulis Test Step" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="pic">PIC</label>
                    <select name="pic" id="pic" class="form-control">
                        <option value="">Select PIC</option>
                        <?php foreach ($developer as $dev): ?>
                            <option value="<?php echo $dev->id; ?>"><?php echo $dev->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="open" class="text-danger">Open</option>
                        <option value="ready to test">Ready to test</option>
                        <option value="close">Close</option>
                    </select>
                </div>
                <div class="form-group">
                <label for="QA Note">Qa Note</label>
                    <textarea name="qa_note" id="qa_note" rows="7" placeholder="Tulis judul" class="form-control"></textarea>
                </div>
                <div class="form-group">
                <label for="Dev Note">Dev Note</label>
                    <textarea name="dev_note" id="dev_note" rows="8" placeholder="Tulis Test Case" class="form-control"></textarea>
                </div>
                <div class="form-group">
                <label for="Dev Pic">Dev Pic</label>
                    <textarea name="dev_pic" id="dev_pic" rows="9" placeholder="Tulis Test Case" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="severity">Severity</label>
                    <select name="severity" id="severity" class="form-control">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="file" class="form-control" id="attachment" name="attachment">
                        <small class="form-text text-muted">Pilih berkas dengan format jpg, jpeg, png, atau gif Max. 2 MB</small>
                    </div>
                    <!-- Add an <img> tag to display the uploaded image -->
                    <img id="image-preview" src="#" alt="Uploaded Image" style="max-width:100%; max-height:200px; display:none;">
                </div>
                <div class="form-group">
                    <input type="hidden" class="form-control" id="nama" name="nama" value="<?= $user['name'] ?>">
                    <input type="hidden" class="form-control" id="level" name="level" value="<?= $user['jabatan'] ?>">
                    <input type="hidden" class="form-control" id="kode" name="kode" value="<?= $user['kode'] ?>">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<!-- end modal medium -->
    <!-- Modal View -->
    <?php foreach ($buglist as $lbu) : ?>
        <div class="modal fade" id="viewModal<?= $lbu->id ?>" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewModalLabel">Detail Bug List</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <tr>
                                <td>Tanggal:</td>
                                <td><?= date('Y-m-d / h:i A', strtotime($lbu->tanggal)) ?></td>
                            </tr>
                            <tr>
                                <td>Modul:</td>
                                <td><?= $lbu->modul ?></td>
                            </tr>
                            <tr>
                                <td>Test Case:</td>
                                <td><?= $lbu->test_case ?></td>
                            </tr>
                            <tr>
                                <td>Test Step:</td>
                                <td><?= $lbu->test_step ?></td>
                            </tr>
                            <tr>
                                <td>PIC:</td>
                                <td><?= $lbu->pic ?></td>
                            </tr>
                            <tr>
                                <td>Status:</td>
                                <td><?= $lbu->status ?></td>
                            </tr>
                            <tr>
                                <td>Qa Note:</td>
                                <td><?= $lbu->qa_note ?></td>
                            </tr>
                            <tr>
                                <td>Dev Note:</td>
                                <td><?= $lbu->dev_note ?></td>
                            </tr>
                            <tr>
                                <td>Severity:</td>
                                <td><?= $lbu->severity ?></td>
                            </tr>
                            <tr>
                                <td>Screenshoot:</td>
                                <td>
                                    <img src="<?= base_url('assets/lampiran/' . $lbu->screenshoot) ?>" alt="Uploaded Image" style="max-width:200px; max-height:200px;">
                                </td>
                            </tr>
                            <!-- Add more fields as needed -->
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<!-- screanshootmodal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Screenshoot</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Screenshoot" class="img-fluid">
            </div>
        </div>
    </div>
</div>

    <?php $this -> load -> view('include/loadjs'); ?>
    <script src="<?= base_url("assets/") ?>javascript/search_filter.js"></script>
    <script>
        function showImageModal(imageUrl) {
            document.getElementById('modalImage').src = imageUrl;
        }
    </script>
    <script src="path/to/lightbox.js"></script>
