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
                <img src="<?= base_url('assets/lampiran/' . $lbu->screenshoot) ?>" alt="Uploaded Image" style="max-width:200px; max-height:200px;">
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