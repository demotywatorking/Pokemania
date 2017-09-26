<?php if (!isset($_GET['ajax'])) echo '<div class="col-xs-12 col-sm-8 col-md-9" id="prawo">'; ?>
<div class="panel-heading"><span>WALKA Z LIDEREM SALI</span></div>
<div class="panel-body">
    <?php if (isset($this->blad)) : echo $this->blad;
    else : ?>
    <div class="row nomargin">
        <div class="col-xs-12 nopadding">
            <div class="row nomargin">
                <div class="col-xs-12 col-sm-6">
                    <div class="row nomargin">
                        <div class="col-xs-4">
                            <?= $this->walka ?>
                            <div class="row text-center">
                                <button class="btn btn-primary center" id="">POWRÓT</button>
                            </div>
                            <?php endif; ?>

