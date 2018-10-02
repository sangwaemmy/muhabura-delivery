<?php
    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    /**
     * Description of fin_books_sum_views
     *
     * SANGWA CODEGURU LTD sangwa22@gmail.com
     */
    class fin_books_sum_views {

        function get_sums() {//This gets the sum by the class name 
            return $sql = "select  account.acc_class, min(account.account_id), min(journal_entry_line.entry_date) as mind,max(journal_entry_line.entry_date) as maxd,  min(account_class.name), sum(journal_entry_line.amount) as amount,min(journal_entry_line.dr_cr)
                   from account 
                join journal_entry_line on journal_entry_line.accountid=account.account_id
                join account_class on account_class.account_class_id=account.acc_class
                where   journal_entry_line.dr_cr=:d_c 
                and   account_class.name=:class  
                and journal_entry_line.entry_date>=:min 
                and journal_entry_line.entry_date<=:max 
                group by account.acc_class";
        }

        function get_ledger_sum_by_accname() {
            return $sql = "select min(journal_entry_line.entry_date) as mind,max(journal_entry_line.entry_date) as maxd, account.account_id,min(account_class.name), sum(journal_entry_line.amount) as amount,min(journal_entry_line.dr_cr)
                   from account 
                join journal_entry_line on journal_entry_line.accountid=account.account_id
                join account_class on account_class.account_class_id=account.acc_class
                where   journal_entry_line.dr_cr=:d_c 
                and   account.account_id=:account  
                and journal_entry_line.entry_date>=:min 
                and journal_entry_line.entry_date<=:max 
                group by account.account_id";
        }

        function get_acc_sum_by_acc_nodate_range() {//The first use is "inside joural_entry_line", to get the sum on each account
            return $sql = "select min(journal_entry_line.entry_date) as mind,max(journal_entry_line.entry_date) as maxd, account.account_id,min(account_class.name), sum(journal_entry_line.amount) as amount,min(journal_entry_line.dr_cr)
                   from account 
                join journal_entry_line on journal_entry_line.accountid=account.account_id
                join account_class on account_class.account_class_id=account.acc_class
                where   journal_entry_line.dr_cr=:d_c 
                and journal_entry_line.entry_date>=:min 
                and journal_entry_line.entry_date<=:max 
                and   account.account_id=:account  
                group by account.account_id";
        }

        function get_ledger_by_accname() {
            return $sql = "select journal_entry_line.entry_date ,journal_entry_line.entry_date , account.account_id,account_class.name, journal_entry_line.amount,journal_entry_line.dr_cr
                   from account 
                join journal_entry_line on journal_entry_line.accountid=account.account_id
                join account_class on account_class.account_class_id=account.acc_class
                where   journal_entry_line.dr_cr=:d_c 
                and   account.account_id=:account  
                and journal_entry_line.entry_date>=:min 
                and journal_entry_line.entry_date<=:max 
                ";
        }

        function get_by_accname_no_acc() {
            return $sql = "select sum( journal_entry_line.amount) as amount
                   from account 
                join journal_entry_line on journal_entry_line.accountid=account.account_id
                join account_class on account_class.account_class_id=account.acc_class
                where   journal_entry_line.dr_cr=:d_c                
                and journal_entry_line.entry_date>=:min 
                and journal_entry_line.entry_date<=:max 
                
                ";
        }

        function list_account_for_ledger() {
            return "select max(journal_entry_line.entry_date) as entry_date, account.account_id,max( account.name) as name from account
                join journal_entry_line on journal_entry_line.accountid=account.account_id
                       where account.is_contra_acc='yes' 
                       and journal_entry_line.entry_date>=:min_date
                and journal_entry_line.entry_date<=:max_date 
                group by account.account_id 
                order by entry_date asc";
        }

        function get_account_sign() {
            return " select account_class.name from account
                join account_class on account_class.account_class_id=account.acc_class
                where account.account_id=:acc";
        }

        // <editor-fold defaultstate="collapsed" desc="-----get_journal_based query--------">

        function journal_based_query() {
            return $sql = "  select  p_type_project.p_type_project_id, p_type_project.name as prj_type,
                 p_budget_prep.p_budget_prep_id,   min(p_budget_prep.name) as proj_name,min(p_budget_prep.budget_type) as budget_type,
                    count(p_activity.name) as p_act_name,sum(p_activity.amount) as p_act_amount,
                    count(journal_entry_line.journal_entry_line_id) as journal_entry_line_id,
                    count( party.name )as party , 
                    count(journal_entry_line.category) as category,   
                    count(journal_entry_line.accountid) as accountid,
                    count(journal_entry_line.dr_cr) as dr_cr,
                    sum(journal_entry_line.amount) as jrnl_amount ,
                    count(journal_entry_line.memo) as memo,
                    count(journal_entry_line.journal_entry_header) as journal_entry_header ,
                    count(journal_entry_line.entry_date) as entry_date,
                    count(account.name) as accountname
                    from journal_entry_line
                        join account on account.account_id=journal_entry_line.accountid
                        join account_class on account_class.account_class_id=account.acc_class
                        join account_type on account.acc_type=account_type.account_type_id
                        join p_activity on journal_entry_line.activity=p_activity.p_activity_id
                        join p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id
                        join project_expectations on project_expectations.project_expectations_id=p_activity.budget_type
                        join p_type_project on p_type_project.p_type_project_id=p_budget_prep.project_type
                        join journal_entry_header on journal_entry_line.journal_entry_header=journal_entry_header_id
                        join party on party.party_id=journal_entry_header.party
                        where journal_entry_line.entry_date>=:min_date
                        and journal_entry_line.entry_date<=:max_date 
                        group by  p_budget_prep.p_budget_prep_id 
                        order by p_type_project.p_type_project_id,p_budget_prep.p_budget_prep_id ";
        }

        function journal_based_query_acc_class($class) {
            return $sql = "  select  p_type_project.p_type_project_id, p_type_project.name as prj_type,
                    p_budget_prep.name as project,
                    p_type_project.name as budget,
                    journal_entry_line.journal_entry_line_id, party.name as party, journal_entry_line.category as category,   journal_entry_line.accountid as accountid,journal_entry_line.dr_cr as dr_cr,journal_entry_line.amount as amount, journal_entry_line.memo as memo,journal_entry_line.journal_entry_header as journal_entry_header , journal_entry_line.entry_date as entry_date,
                    account.name as accountname,
                    user.Firstname,user.Lastname,
                    p_activity.name as activity
                    from journal_entry_line
                        join journal_entry_header on journal_entry_line.journal_entry_header=journal_entry_header.journal_entry_header_id
                        join party on journal_entry_header.party=party.party_id
                        join user on user.StaffID=journal_entry_line.user
                        join account on account.account_id=journal_entry_line.accountid
                        join account_class on account_class.account_class_id=account.acc_class
                        join account_type on account.acc_type=account_type.account_type_id
                        join p_activity on journal_entry_line.activity=p_activity.p_activity_id
                        join p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id
                        join p_type_project on p_type_project.p_type_project_id=p_budget_prep.project_type
                        where journal_entry_line.entry_date>=:min_date
                        and journal_entry_line.entry_date<=:max_date 
                        and account_class.name='" . $class . "'";
        }

        function get_budgets() {
            return $sql = " select p_type_project.p_type_project_id, p_type_project.name as prj_type from p_type_project";
        }

        function get_tot_rev_exp($rev_exp) {
            return $sql = " select  min(p_budget_prep.p_budget_prep_id),max(  p_budget_prep.project_type), max( p_budget_prep.user),max(  p_budget_prep.entry_date), max( p_budget_prep.budget_type) ,max(  p_budget_prep.activity_desc),sum( p_activity.amount) as tot,  max( p_budget_prep.name) as name, max(user.Firstname),max( user.Lastname),max( p_type_project.name) as type
                    from p_budget_prep
                    join user on p_budget_prep.user = user.StaffID 
                    join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id
                    join p_activity on p_activity.project=p_budget_prep.p_budget_prep_id 
                     join project_expectations on project_expectations.project_expectations_id=p_activity.budget_type"
                    . "  where  p_type_project.p_type_project_id=:project and "
                    . " project_expectations.name='" . $rev_exp . "' "
                    . " group by p_budget_prep.p_budget_prep_id ";
        }

        function get_implementation_rev_exp($rev_exp) {//This is by type_project
            return $sql = "select sum(journal_entry_line.amount) as tot from journal_entry_line
                            join account on account.account_id=journal_entry_line.accountid
                            join account_class on account_class.account_class_id=account.acc_class
                            join account_type on account.acc_type=account_type.account_type_id
                            join p_activity on journal_entry_line.activity=p_activity.p_activity_id
                            join p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id
                            join p_type_project on p_type_project.p_type_project_id=p_budget_prep.project_type
                             where account.book_section='" . $rev_exp . "'
                             and p_type_project.p_type_project_id=:project
                             group by p_type_project.p_type_project_id
                            ";
        }

        function get_impl_byrev_exp_proj($rev_exp) {
            return $sql = " select sum(journal_entry_line.amount) as tot from journal_entry_line
                            join account on account.account_id=journal_entry_line.accountid
                            join account_class on account_class.account_class_id=account.acc_class
                            join account_type on account.acc_type=account_type.account_type_id
                            join p_activity on journal_entry_line.activity=p_activity.p_activity_id
                            join p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id
                            join p_type_project on p_type_project.p_type_project_id=p_budget_prep.project_type
                             where account.book_section='" . $rev_exp . "'
                             and p_budget_prep.p_budget_prep_id=:project
                             group by p_type_project.p_type_project_id
                            ";
        }

        function list_prepared_rev_exp() {
            return $sql = "select p_budget_prep.name as proj_name, p_type_project.p_type_project_id, p_activity.amount as p_act_amount, p_activity.name as p_act_name from p_activity
                           join p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id
                           join p_type_project on p_type_project.p_type_project_id=p_budget_prep.project_type
                           and p_type_project.p_type_project_id=:id
                           group by p_type_project.p_type_project_id ";
        }

        function list_implementation_rev_exp($rev_exp) {
            return $sql = "select  p_budget_prep.name as proj_name, p_type_project.p_type_project_id, p_activity.amount as p_act_amount, p_activity.name as p_act_name from journal_entry_line
                            join account on account.account_id=journal_entry_line.accountid
                            join account_class on account_class.account_class_id=account.acc_class
                            join account_type on account.acc_type=account_type.account_type_id
                            join p_activity on journal_entry_line.activity=p_activity.p_activity_id
                            join p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id
                            join p_type_project on p_type_project.p_type_project_id=p_budget_prep.project_type
                             where account.book_section='" . $rev_exp . "'
                             and p_type_project.p_type_project_id=:id
                             group by p_type_project.p_type_project_id
                            ";
        }

        function get_proj_exp_rev_id($project, $rev_exp) {
            return $sql = "SELECT sum(p_activity.amount) as sum FROM  p_budget_prep
                            join p_type_project on p_type_project.p_type_project_id=p_budget_prep.project_type                             
                            join p_activity on p_activity.project=p_budget_prep.p_budget_prep_id
                            join project_expectations on project_expectations.project_expectations_id=p_activity.budget_type
                            where p_budget_prep.p_budget_prep_id='" . $project . "' and project_expectations.name='" . $rev_exp . "'";
        }

        function list_budget_impl_by_date() {
            
        }

// </editor-fold>
    }

    class any_report_details {

        private $parm = '';

        function fnx1($min_date, $max_date, $sql, $label, $value, $pdf_post = array()) {
            ?><div class="parts full_center_two_h heit_free no_paddin_shade_no_Border xx_titles blue_text">Budget Line prepared on <?php echo $min_date . ' -  ' . $max_date ?></div><?php
            $database = new dbconnection();
            $db = $database->openConnection();
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
            ?>
            <script>
                $('.row_font_h1').hover(function () {
                    $('.no_padding_print_btn').css('opacity', '1');
                });
                $('.row_font_h1').mouseleave(function () {
                    $('.no_padding_print_btn').css('opacity', '0');
                });
            </script>
            <table class="white_report_table margin_free ">
                <thead><tr>
                        <td> Budget Line </td>                       
                    </tr>
                </thead>
                <?php
                $pages = 1;

                while ($row = $stmt->fetch()) {
                    ?><tr  class="row_font_h1"   data-bind="p_budget_prep"> 
                        <td class="project_type_id_cols p_budget_prep " title="p_budget_prep" >
                            <?php $this->fnx1_label_value($label, $row[$value]) ?>
                            <div class="parts no_paddin_shade_no_Border">
                                <table style="margin:0px;" class="margin_free sub_datatable_leftoff">
                                    <td class="no_paddin_shade_no_Border no_padding_print_btn">
                                        <form action="../web_exports/excel_export.php" method="post">
                                            <input type="hidden" name="account" value="a"/>
                                            <input type="hidden" name="account" value="a"/>
                                            <input type="submit" name="export" class="btn_export btn_export_excel margin_free" value="Export"/>
                                        </form>
                                    </td>
                                    <td class="no_paddin_shade_no_Border no_padding_print_btn">
                                        <form action="../print_more_reports/print_budget_line_bydates.php" target="blank" method="post">
                                            <?php
                                            $params = '';
                                            foreach ($pdf_post as $parm) {
                                                $params .= ' <input type="hidden" name="' . $parm . '" value=" ' . $row[$parm] . '"/>';
                                            }
                                            echo $params;
                                            ?>
                                            <input type="submit" name="export" class="btn_export btn_export_pdf margin_free" value="Export"/>
                                        </form>
                                    </td>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php // $this->list_p_budget_prep_by_type_project($min, $max, $row['p_type_project_id'])            ?>
                        </td> 
                    </tr>
                    <tr class="leave_bottom_space">
                        <td></td>
                    </tr>

                    <?php
                    $pages += 1;
                }
                ?></table><?php
        }

        function fnx1_btn_params($parm, $param = array()) {
            $params = '';
            foreach ($param as $parm) {
                $params .= ' <input type="hidden" name="account" value="' . $parm . '"/>';
            }
            echo $params;
        }

        function fnx1_label_value($label, $value) {
            echo '<div class="parts no_paddin_shade_no_Border type_lbl">' . $label . ' : ' . $value . '  </div>';
        }

    }
    