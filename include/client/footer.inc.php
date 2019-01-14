
        </div>
    </div>
    
    <div class="modal fade" id="myload" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <img class="center-block centered" src="<?php echo ROOT_PATH; ?>images/FhHRx-Spinner.gif"/>
                        </div>
                        <div class="col-md-8">
                            <h4><?php echo __('Please Wait!');?></h4>
                            <p><?php echo __('Please wait... it will take a second!');?></p>
                        </div>
                    </div>
                </div>
              </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- theme switcher -->
    
    </div>
</div>
<?php
if (($lang = Internationalization::getCurrentLanguage()) && $lang != 'en_US') { ?>
    <script type="text/javascript" src="ajax.php/i18n/<?php
        echo $lang; ?>/js"></script>
<?php } ?>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo ROOT_PATH; ?>assets/adminlte/components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo ROOT_PATH; ?>assets/adminlte/components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo ROOT_PATH; ?>assets/adminlte/components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo ROOT_PATH; ?>assets/adminlte/js/adminlte.min.js"></script>
<!-- JqueryStorageApi -->
<script src="<?php echo ROOT_PATH; ?>assets/adminlte/components/jquery-storage/jquery.storageapi.min.js"></script>
<!-- PACE -->
<script src="<?php echo ROOT_PATH; ?>assets/adminlte/components/PACE/pace.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!--<script src="<?php echo ROOT_PATH; ?>assets/adminlte/js/app.js"></script>-->
<script src="<?php echo ROOT_PATH; ?>assets/adminlte/js/ui-toggle-class.js"></script> 
<script src="<?php echo ROOT_PATH; ?>assets/adminlte/js/hawk-app.js"></script>
<script type="text/javascript">
    getConfig().resolve(<?php
        include INCLUDE_DIR . 'ajax.config.php';
        $api = new ConfigAjaxAPI();
        print $api->client(false);
    ?>);
</script>
</body>
</html>
