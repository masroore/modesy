<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
    <div class="container">
        <div class="row">

            <div class="col-12">
                <div class="blog-content">
                    <nav class="nav-breadcrumb" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo html_escape($page->title); ?></li>
                        </ol>
                    </nav>
                    <?php if ($page->title_active == 1): ?>
                        <h1 class="page-title"><?php echo html_escape($page->title); ?></h1>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-text-content">
                                <?php echo $page->page_content; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Wrapper End-->

<script>
    $(function () {
        $('.page-text-content iframe').wrap('<div class="embed-responsive embed-responsive-16by9"></div>');
        $('.page-text-content iframe').addClass('embed-responsive-item');
    });
</script>
