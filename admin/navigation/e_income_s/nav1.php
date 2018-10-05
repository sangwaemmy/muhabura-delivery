<div class="parts eighty_centered x_height_3x income_sub_p pages page1">
    <a href="report_journal_entry.php">  income</a>
    <div class="parts pages_bottom link_cursor">
        <?php
            require_once '../web_db/other_fx.php';
            $other = new other_fx();
//            echo number_format($other->get_account_balance('sales revenue'));
        ?>
    </div>


</div>
<div class="parts eighty_centered x_height_3x income_sub_p pages page2">
    <a href="report_journal_entry.php">  Expense</a>
    <div class="parts pages_bottom link_cursor">
        <?php
//            require_once '../web_db/other_fx';
            $other = new other_fx();
//            echo number_format($other->get_account_balance('sales revenue'));
        ?>
    </div>
</div>
<div class="parts eighty_centered x_height_3x income_sub_p pages page3">
    <a href="report_journal_entry.php">  Net</a>
    <div class="parts pages_bottom link_cursor">
        <?php
            $other = new other_fx();
//            echo number_format($other->get_sum_income_or_expenses('income') - $other->get_sum_income_or_expenses('expense'));
        ?>
    </div>
</div>


