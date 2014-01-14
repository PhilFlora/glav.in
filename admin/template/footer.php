                </div> <!-- #main -->
            </div> <!-- #main-container -->

        </div><!-- #admin-wrap -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?php echo base_url(); ?>admin/template/js/vendor/jquery-1.10.1.min.js"><\/script>')</script>

        <script src="<?php echo base_url(); ?>admin/template/js/main.js"></script>

        <script src="<?php echo base_url(); ?>admin/template/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>admin/template/js/placeMe.js"></script>
        <script src="<?php echo base_url(); ?>admin/template/js/ckeditor/ckeditor.js"></script>
        <script>
            window.onload = function() {
                CKEDITOR.replace( 'page-content' );
            };

            $(function() {

                // Correct the user while typing the filename
                $('input[name="page_name"]').keydown(function(e) {
                    var keyVal = ( e.charCode ? e.charCode : ( e.keyCode ? e.keyCode : e.which ) );
                    
                    if ( ( keyVal == 0x41 && e.ctrlKey === true ) || ( keyVal == 0x63 && e.ctrlKey === true ) || // Allow: Ctrl+A, or Ctrl+C
                        ( keyVal == 0x56 && e.ctrlKey === true ) || ( keyVal == 0x58 && e.ctrlKey === true ) ) { // or Ctrl+V, or Ctrl+X
                        return;
                    } else if ( keyVal == 0x20 ) { // If user added a space, replace it with underscore
                        $('input[name="page_name"]').val($(this).val() + '-');
                        return false;
                    } else if ( keyVal >= 0x41 && keyVal <= 0x5A ) { // Convert uppercase letters to lowercase
                        $('input[name="page_name"]').val($(this).val() + String.fromCharCode(keyVal).toLowerCase());
                        return false;
                    }
                    
                }); 

            });
            
            // Check Description Length
            $().ready(function(){
                $('input[name="page_description"]').keypress(function(e) {
                    var len = $(this).val().length,
                        maxlen = 160,
                        keyVal = ( e.charCode ? e.charCode : ( e.keyCode ? e.keyCode : e.which ) );
                    
                    if ( keyVal == 8 || keyVal == 9 || keyVal == 13 || //Allow: Backspace, Tab, Enter
                            ( keyVal == 0x41 && e.ctrlKey === true ) || ( keyVal == 0x63 && e.ctrlKey === true ) || // Allow: Ctrl+A, or Ctrl+C
                                ( keyVal == 0x56 && e.ctrlKey === true ) || ( keyVal == 0x58 && e.ctrlKey === true ) ) { // or Ctrl+V, or Ctrl+X
                        return;
                    } else if ( len > maxlen - 1 ) {
                        return false;
                    }
                });
            });
        </script>                   

    </body>
</html>