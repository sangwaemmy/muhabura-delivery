

<?php

    /**
     * Description of extra_sub_menus
     *
     * SANGWA at CODEGURU
     */
    class extra_sub_menus {

        function get_budget_line_newdata_smenu() {
            return $mnu = ""
                    . "";
            ?>
            <div class="parts sub_menus">
                <a href="#">Search</a>
                <a href="#"></a>
            </div>
            <?php
        }

        function get_budget_line_datalist_smenu() {
            ?>
            <div class="parts full_center_two_h heit_free margin_free no_paddin_shade_no_Border">
                <div class="parts no_paddin_shade_no_Border smenu_item margin_free" id="smenu1">
                    Preparation
                </div>
                <div class="parts no_paddin_shade_no_Border smenu_item margin_free" id="smenu2">
                    Implementation
                </div>
            </div> 
            <?php
        }

        function get_request() {
            ?> 
            <div class="parts full_center_two_h heit_free margin_free no_paddin_shade_no_Border">
                <div class="parts no_paddin_shade_no_Border smenu_item margin_free" id="smenu1">
                    All requests
                </div>
                <div class="parts no_paddin_shade_no_Border smenu_item margin_free" id="smenu2">
                    Request by location
                </div>
            </div> 

            <?php
        }

    }
    