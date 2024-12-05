<?php
require($_SERVER['DOCUMENT_ROOT'].'/CAFC/db.php');
include($_SERVER['DOCUMENT_ROOT']."/CAFC/auth.php");
include($_SERVER['DOCUMENT_ROOT']."/CAFC/headfoot.php");
require_once($_SERVER['DOCUMENT_ROOT']."/CAFC/function_library.php");
$table_name="tr_pay_age_data";
$unitname=$_SESSION['unitname'];
$fiscal=$_SESSION['fiscal'];
$unitfilter=fn_unitfilter($_SESSION['unitname']);
include("fs_period_resolution.php");
#echo "current fiscal:".$fiscal;
#echo "<br>";
#echo "preceding fiscal:".$preceding_ytd_fiscal;
#echo "<br>";
#echo "previous FY fiscal:".$prev_fy_fiscal;
#echo "<br>";
#echo "previous ytd fiscal:".$prev_ytd_fiscal;
#echo "<br>";
#echo "prec 2 prec ytd fiscal:". $preceding_2_ytd_fiscal;
#echo "<br>";
#echo "prec 2 prev ytd fiscal:" .$prev_preceding_ytd_fiscal;
$q="
With 
cte1_b as
(    SELECT 
        a.glcode AS glcode,
        a.cb AS cb,
        a.fiscal AS fiscal,
        a.unitname AS unitname,
		b.pg_code,b.pgdesc,
        'TRIALBAL' AS source_det
    FROM
        tbalance a
        LEFT JOIN coa_with_grouping b ON a.glcode = b.glcode
    WHERE
        a.fiscal = '$fiscal'
        AND a.unitname LIKE '$unitfilter'
        AND b.pg_code LIKE 'PL%'
    UNION ALL
    SELECT 
        rce_all_2.glcode AS glcode,
        rce_all_2.cb AS cb,
        rce_all_2.fiscal AS fiscal,
        rce_all_2.unitname AS unitname,
		rce_all_2.pg_code,rce_all_2.pgdesc,
        'RECLASS' AS source_det
    FROM
        rce_all_2
    WHERE
        rce_all_2.fiscal = '$fiscal'
        AND rce_all_2.unitname LIKE '$unitfilter'
        AND rce_all_2.pg_code LIKE 'PL%'
),
cte2_b as
(    SELECT 
        a.glcode AS glcode,
        a.cb AS cb,
        a.fiscal AS fiscal,
        a.unitname AS unitname,
		b.pg_code,b.pgdesc,
        'TRIALBAL' AS source_det
    FROM
        tbalance a
        LEFT JOIN coa_with_grouping b ON a.glcode = b.glcode
    WHERE
        a.fiscal = '$prev_ytd_fiscal'
        AND a.unitname LIKE '$unitfilter'
        AND b.pg_code LIKE 'PL%'
    UNION ALL
    SELECT 
        rce_all_2.glcode AS glcode,
        rce_all_2.cb AS cb,
        rce_all_2.fiscal AS fiscal,
        rce_all_2.unitname AS unitname,
		rce_all_2.pg_code,rce_all_2.pgdesc,
        'RECLASS' AS source_det
    FROM
        rce_all_2
    WHERE
        rce_all_2.fiscal = '$prev_ytd_fiscal'
        AND rce_all_2.unitname LIKE '$unitfilter'
        AND rce_all_2.pg_code LIKE 'PL%'
),
cte3_b as
(    SELECT 
        a.glcode AS glcode,
        a.cb AS cb,
        a.fiscal AS fiscal,
        a.unitname AS unitname,
		b.pg_code,b.pgdesc,
        'TRIALBAL' AS source_det
    FROM
        tbalance a
        LEFT JOIN coa_with_grouping b ON a.glcode = b.glcode
    WHERE
        a.fiscal = '$preceding_ytd_fiscal'
        AND a.unitname LIKE '$unitfilter'
        AND b.pg_code LIKE 'PL%'
    UNION ALL
    SELECT 
        rce_all_2.glcode AS glcode,
        rce_all_2.cb AS cb,
        rce_all_2.fiscal AS fiscal,
        rce_all_2.unitname AS unitname,
		rce_all_2.pg_code,rce_all_2.pgdesc,
        'RECLASS' AS source_det
    FROM
        rce_all_2
    WHERE
        rce_all_2.fiscal = '$preceding_ytd_fiscal'
        AND rce_all_2.unitname LIKE '$unitfilter'
        AND rce_all_2.pg_code LIKE 'PL%'
),
cte33_b as
(    SELECT 
        a.glcode AS glcode,
        a.cb AS cb,
        a.fiscal AS fiscal,
        a.unitname AS unitname,
		b.pg_code,b.pgdesc,
        'TRIALBAL' AS source_det
    FROM
        tbalance a
        LEFT JOIN coa_with_grouping b ON a.glcode = b.glcode
    WHERE
        a.fiscal = '$preceding_qtr_fiscal'
        AND a.unitname LIKE '$unitfilter'
        AND b.pg_code LIKE 'PL%'
    UNION ALL
    SELECT 
        rce_all_2.glcode AS glcode,
        rce_all_2.cb AS cb,
        rce_all_2.fiscal AS fiscal,
        rce_all_2.unitname AS unitname,
		rce_all_2.pg_code,rce_all_2.pgdesc,
        'RECLASS' AS source_det
    FROM
        rce_all_2
    WHERE
        rce_all_2.fiscal = '$preceding_qtr_fiscal'
        AND rce_all_2.unitname LIKE '$unitfilter'
        AND rce_all_2.pg_code LIKE 'PL%'
),
cte4_b as
(    SELECT 
        a.glcode AS glcode,
        a.cb AS cb,
        a.fiscal AS fiscal,
        a.unitname AS unitname,
		b.pg_code,b.pgdesc,
        'TRIALBAL' AS source_det
    FROM
        tbalance a
        LEFT JOIN coa_with_grouping b ON a.glcode = b.glcode
    WHERE
        a.fiscal = '$prev_fy_fiscal'
        AND a.unitname LIKE '$unitfilter'
        AND b.pg_code LIKE 'PL%'
    UNION ALL
    SELECT 
        rce_all_2.glcode AS glcode,
        rce_all_2.cb AS cb,
        rce_all_2.fiscal AS fiscal,
        rce_all_2.unitname AS unitname,
		rce_all_2.pg_code,rce_all_2.pgdesc,
        'RECLASS' AS source_det
    FROM
        rce_all_2
    WHERE
        rce_all_2.fiscal = '$prev_fy_fiscal'
        AND rce_all_2.unitname LIKE '$unitfilter'
        AND rce_all_2.pg_code LIKE 'PL%'
),
cte5_b as
(    SELECT 
        a.glcode AS glcode,
        a.cb AS cb,
        a.fiscal AS fiscal,
        a.unitname AS unitname,
		b.pg_code,b.pgdesc,
        'TRIALBAL' AS source_det
    FROM
        tbalance a
        LEFT JOIN coa_with_grouping b ON a.glcode = b.glcode
    WHERE
        a.fiscal = '$preceding_2_ytd_fiscal'
        AND a.unitname LIKE '$unitfilter'
        AND b.pg_code LIKE 'PL%'
    UNION ALL
    SELECT 
        rce_all_2.glcode AS glcode,
        rce_all_2.cb AS cb,
        rce_all_2.fiscal AS fiscal,
        rce_all_2.unitname AS unitname,
		rce_all_2.pg_code,rce_all_2.pgdesc,
        'RECLASS' AS source_det
    FROM
        rce_all_2
    WHERE
        rce_all_2.fiscal = '$preceding_2_ytd_fiscal'
        AND rce_all_2.unitname LIKE '$unitfilter'
        AND rce_all_2.pg_code LIKE 'PL%'
),
cte6_b as
(    SELECT 
        a.glcode AS glcode,
        a.cb AS cb,
        a.fiscal AS fiscal,
        a.unitname AS unitname,
		b.pg_code,b.pgdesc,
        'TRIALBAL' AS source_det
    FROM
        tbalance a
        LEFT JOIN coa_with_grouping b ON a.glcode = b.glcode
    WHERE
        a.fiscal = '$prev_preceding_ytd_fiscal'
        AND a.unitname LIKE '$unitfilter'
        AND b.pg_code LIKE 'PL%'
    UNION ALL
    SELECT 
        rce_all_2.glcode AS glcode,
        rce_all_2.cb AS cb,
        rce_all_2.fiscal AS fiscal,
        rce_all_2.unitname AS unitname,
		rce_all_2.pg_code,rce_all_2.pgdesc,
        'RECLASS' AS source_det
    FROM
        rce_all_2
    WHERE
        rce_all_2.fiscal = '$prev_preceding_ytd_fiscal'
        AND rce_all_2.unitname LIKE '$unitfilter'
        AND rce_all_2.pg_code LIKE 'PL%'
),
	cte1 as 
		(select 
		pg_code,pgdesc, ROUND(sum(cb),0) amt_curr_ytd
		from cte1_b
		where fiscal='$fiscal' and unitname like '$unitfilter'
		and pg_code like 'PL%'
		group by pg_code,pgdesc),
	cte2 as 
		(select pg_code,ROUND(sum(cb),0) amt_prev_ytd
		from cte2_b
		where fiscal='$prev_ytd_fiscal' and unitname like '$unitfilter'
		and pg_code like 'PL%'
		group by pg_code,pgdesc),
	cte3 as 
		(select pg_code,ROUND(sum(cb),0) amt_prec_ytd
		from cte3_b
		where fiscal='$preceding_ytd_fiscal' and unitname like '$unitfilter'
		and pg_code like 'PL%'
		group by pg_code,pgdesc),
	cte33 as 
		(select pg_code,ROUND(sum(cb),0) amt_prec_ytd_4_qtr
		from cte33_b
		where fiscal='$preceding_qtr_fiscal' and unitname like '$unitfilter'
		and pg_code like 'PL%'
		group by pg_code,pgdesc),
	cte4 as 
		(select pg_code,ROUND(sum(cb),0) amt_prev_fy
		from cte4_b
		where fiscal='$prev_fy_fiscal' and unitname like '$unitfilter'
		and pg_code like 'PL%'
		group by pg_code,pgdesc),
	cte5 as 
		(select pg_code,ROUND(sum(cb),0) amt_prec_2_ytd
		from cte5_b
		where fiscal='$preceding_2_ytd_fiscal' and unitname like '$unitfilter'
		and pg_code like 'PL%'
		group by pg_code,pgdesc),
	cte6 as 
		(select pg_code,ROUND(sum(cb),0) amt_prev_prec_ytd
		from cte6_b
		where fiscal='$prev_preceding_ytd_fiscal' and unitname like '$unitfilter'
		and pg_code like 'PL%'
		group by pg_code,pgdesc),
	cte7 as
		(select pg_code,description as pgdesc from primary_grouping where pg_code like 'PL%')
select  cte7.pg_code, cte7.pgdesc,cte1.amt_curr_ytd, 
cte2.amt_prev_ytd,
cte3.amt_prec_ytd,
cte33.amt_prec_ytd_4_qtr,
cte4.amt_prev_fy,
cte6.amt_prev_prec_ytd,
(ifnull(cte1.amt_curr_ytd,0)-ifnull(cte3.amt_prec_ytd,0)) as amt_curr_qtr,
(ifnull(cte33.amt_prec_ytd_4_qtr,0)-ifnull(cte5.amt_prec_2_ytd,0)) as amt_prec_qtr,
(ifnull(cte2.amt_prev_ytd,0)-ifnull(cte6.amt_prev_prec_ytd,0)) as amt_prev_qtr
from cte7 left join cte1 using(pg_code)
left join cte2 using(pg_code)
left join cte3 using (pg_code)
left join cte33 using (pg_code)
left join cte4 using (pg_code)
left join cte5 using (pg_code)
left join cte6 using (pg_code)
;
";
$result=mysqli_query($con,$q) or die(mysqli_error($con));
$num_fields=mysqli_num_fields($result);
$fields_array=mysqli_fetch_fields($result);
$fs_values=array();
while($row = mysqli_fetch_assoc($result)) {
	$fs_values[$row['pg_code']]=array
	($row['amt_curr_ytd'],
	$row['amt_prec_ytd'],
	$row['amt_prev_ytd'],
	$row['amt_prev_fy'],
	$row['amt_curr_qtr'],
	$row['amt_prec_qtr'],
	$row['amt_prev_qtr'],
	);
}
$fs_values['Total_Income']=array
	($fs_values['PL00340000'][0]+$fs_values['PL00350000'][0],
	$fs_values['PL00340000'][1]+$fs_values['PL00350000'][1],
	$fs_values['PL00340000'][2]+$fs_values['PL00350000'][2],
	$fs_values['PL00340000'][3]+$fs_values['PL00350000'][3],
	$fs_values['PL00340000'][4]+$fs_values['PL00350000'][4],
	$fs_values['PL00340000'][5]+$fs_values['PL00350000'][5],
	$fs_values['PL00340000'][6]+$fs_values['PL00350000'][6]);
$temp_array=array();
for ($x=0;$x<=6;$x++){
	$temp_array[$x]=$fs_values['PL00360000'][$x]+
		$fs_values['PL00370000'][$x]+
		$fs_values['PL00380000'][$x]+
		$fs_values['PL00390000'][$x]+
		$fs_values['PL00391000'][$x]+
		$fs_values['PL00392000'][$x]+
		$fs_values['PL00393000'][$x]+
		$fs_values['PL00400000'][$x];
}
$fs_values['Total_expenses']=$temp_array;
$temp_array=array();
for ($x=0;$x<=6;$x++){
	$temp_array[$x]=$fs_values['Total_Income'][$x]+$fs_values['Total_expenses'][$x];
}
$fs_values['PBTEI']=$temp_array;
$temp_array=array();
for ($x=0;$x<=6;$x++){
	$temp_array[$x]=$fs_values['PBTEI'][$x]+$fs_values['PL00500000'][$x];
}
$fs_values['PBT']=$temp_array;
$temp_array=array();
for ($x=0;$x<=6;$x++){
	$temp_array[$x]=$fs_values['PL00410000'][$x]+$fs_values['PL00415000'][$x];
}
$fs_values['Tax_expense']=$temp_array;
$temp_array=array();
for ($x=0;$x<=6;$x++){
	$temp_array[$x]=$fs_values['PBT'][$x]+$fs_values['Tax_expense'][$x];
}
$fs_values['PAT']=$temp_array;
$temp_array=array();
for ($x=0;$x<=6;$x++){
	$temp_array[$x]=$fs_values['PAT'][$x]+$fs_values['PL00030000'][$x];
}
$fs_values['TOCI']=$temp_array;
#print_r($totalamt);
#print_r($totalreqd);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>SEBI P&L</title>
<link rel="stylesheet" href="../css/style.css" />
<script src="/sheetJS/xlsx.full.min.js"></script>
<script>
        function downloadTableAsExcel() {
            var table = document.getElementById("dataTable");
            var workbook = XLSX.utils.table_to_book(table, {sheet: "Sheet1"});
            XLSX.writeFile(workbook, "plsebi.xlsx");
        }
		</script>
<style>
th, td {
  padding: 5px;
}
</style>
</head>	
<body>
<div class="form">
<p><a href="../circle_index.php">Circle Home</a> 
| <a href="../home.html">Home</a> 
</p>
<h2>SEBI P&L</h2>
<div style="text-align: left; ">
    <button style="background: linear-gradient(135deg, #4a90e2, #357ab7); color: white; padding: 8px 16px; font-size: 13px; font-weight: 600; border: none; border-radius: 5px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1); text-transform: uppercase; letter-spacing: 0.5px;" 
        onmouseover="this.style.background='linear-gradient(135deg, #357ab7, #2d5f8a)'; this.style.boxShadow='0 5px 8px rgba(0, 0, 0, 0.15)'; this.style.transform='translateY(-1px)';"
        onmouseout="this.style.background='linear-gradient(135deg, #4a90e2, #357ab7)'; this.style.boxShadow='0 3px 5px rgba(0, 0, 0, 0.1)'; this.style.transform='translateY(0px)';"
        onclick="downloadTableAsExcel()">Download as Excel</button>
</div><table id="dataTable"  border="1" width="100%" style="border-collapse:collapse; border-spacing:15px;border-color:lightgray;">
<colgroup>
        <col style="width: 50px;">
        <col style="width: 500px;">
        <col style="width: 70px;">
        <col> 
        <col>
        <col>
        <col> 
        <col>
        <col> 
    </colgroup>
<thead>
<tr>
	<th  rowspan=3><strong>S.No.</strong></th>
	<th  rowspan=3><strong>Particulars</strong></th>
	<th  rowspan=3><strong>Note Ref</strong></th>
	<th colspan=3><strong>Quarter Ended</strong></th>
	<th colspan=2><strong>Year Till Date</strong></th>
	<th><strong>Year Ended</strong></th>
</tr>
<tr>
	<th><strong><?php echo $current_prd_end;?></strong></th>
	<th><strong><?php echo $preceding_prd_end;?></strong></th>
	<th><strong><?php echo $comp_prd_end;?></strong></th>
	<th><strong><?php echo $current_prd_end;?></strong></th>
	<th><strong><?php echo $comp_prd_end;?></strong></th>
	<th><strong><?php echo $prev_fiscal_end;?></strong></th>
</tr>
<tr>
	<th><strong>(Unaudited)</strong></th>
	<th><strong>(Unaudited)</strong></th>
	<th><strong>(Unaudited)</strong></th>
	<th><strong>(Unaudited)</strong></th>
	<th><strong>(Unudited)</strong></th>
	<th><strong>(Audited)</strong></th>
</tr>
</thead>
<tbody>
<tr>
	<td align="center">1</td>
	<td align="Left"><strong>Income</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">
	<a href="fs_pl_note.php?pg_code=PL00340000" target="_blank">a</a>
</td>
	<td align="Left">Revenue from operations</td>
	<td align="center"></td>
	<td align="right"><?php echo inr_format($fs_values['PL00340000'][4]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00340000'][5]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00340000'][6]*-1);?></td> 
	<td align="right"><?php echo inr_format($fs_values['PL00340000'][0]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00340000'][2]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00340000'][3]*-1);?></td>
</tr>
<tr>
	<td align="center"><a href="fs_pl_note.php?pg_code=PL00350000" target="_blank">b</a></td>
	<td align="Left">Other income</td>
	<td align="center"></td>
	<td align="right"><?php echo inr_format($fs_values['PL00350000'][4]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00350000'][5]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00350000'][6]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00350000'][0]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00350000'][2]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00350000'][3]*-1);?></td>
</tr>
<tr>
	<td align="center">c</td>
	<td align="Left"><strong>Total income</strong></td>
	<td align="center"></td>
	<td align="right"><strong><?php echo inr_format($fs_values['Total_Income'][4]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['Total_Income'][5]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['Total_Income'][6]*-1);?></strong></td> 
	<td align="right"><strong><?php echo inr_format($fs_values['Total_Income'][0]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['Total_Income'][2]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['Total_Income'][3]*-1);?></strong></td>
</tr>
<tr>
        <td colspan="9"></td>
</tr>
<tr>
	<td align="center">2</td>
	<td align="Left"><strong>Expenses</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center"><a href="fs_pl_note.php?pg_code=PL00392000" target="_blank">a</a></td>
	<td align="Left">Network Operating Expenses</td>
	<td align="center"></td>
	<td align="right"><?php echo inr_format($fs_values['PL00392000'][4]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00392000'][5]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00392000'][6]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00392000'][0]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00392000'][2]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00392000'][3]);?></td>
</tr>
<tr>
	<td align="center"><a href="fs_pl_note.php?pg_code=PL00391000" target="_blank">b</a></td>
	<td align="Left">Access Charges</td>
	<td align="center"></td>
	<td align="right"><?php echo inr_format($fs_values['PL00391000'][4]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00391000'][5]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00391000'][6]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00391000'][0]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00391000'][2]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00391000'][3]);?></td>
</tr>
<tr>
	<td align="center"><a href="fs_pl_note.php?pg_code=PL00400000" target="_blank">c</a></td>
	<td align="Left">License and spectrum fee</td>
	<td align="center"></td>
	<td align="right"><?php echo inr_format($fs_values['PL00400000'][4]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00400000'][5]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00400000'][6]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00400000'][0]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00400000'][2]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00400000'][3]);?></td>
</tr>
<tr>
	<td align="center"><a href="fs_pl_note.php?pg_code=PL00360000" target="_blank">d</a></td>
	<td align="Left">Employee benefits expense</td>
	<td align="center"></td>
	<td align="right"><?php echo inr_format($fs_values['PL00360000'][4]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00360000'][5]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00360000'][6]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00360000'][0]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00360000'][2]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00360000'][3]);?></td>
</tr>
<tr>
	<td align="center"><a href="fs_pl_note.php?pg_code=PL00393000" target="_blank">e</a></td>
	<td align="Left">Sales & Marketing Expenses</td>
	<td align="center"></td>
	<td align="right"><?php echo inr_format($fs_values['PL00393000'][4]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00393000'][5]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00393000'][6]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00393000'][0]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00393000'][2]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00393000'][3]);?></td>
</tr>
<tr>
	<td align="center"><a href="fs_pl_note.php?pg_code=PL00370000" target="_blank">f</a></td>
	<td align="Left">Finance costs</td>
	<td align="center"></td>
	<td align="right"><?php echo inr_format($fs_values['PL00370000'][4]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00370000'][5]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00370000'][6]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00370000'][0]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00370000'][2]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00370000'][3]);?></td>
</tr>
<tr>
	<td align="center"><a href="fs_pl_note.php?pg_code=PL00380000" target="_blank">g</a></td>
	<td align="Left">Depreciation and amortisation expense</td>
	<td align="center"></td>
	<td align="right"><?php echo inr_format($fs_values['PL00380000'][4]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00380000'][5]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00380000'][6]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00380000'][0]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00380000'][2]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00380000'][3]);?></td>
</tr>
<tr>
	<td align="center"><a href="fs_pl_note.php?pg_code=PL00390000" target="_blank">h</a></td>
	<td align="Left">Other expenses</td>
	<td align="center"></td>
	<td align="right"><?php echo inr_format($fs_values['PL00390000'][4]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00390000'][5]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00390000'][6]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00390000'][0]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00390000'][2]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00390000'][3]);?></td>
</tr>
<tr>
	<td align="center"></td>
	<td align="Left"><strong>Total expenses</strong></td>
	<td align="center"></td>
	<td align="right"><strong><?php echo inr_format($fs_values['Total_expenses'][4]);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['Total_expenses'][5]);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['Total_expenses'][6]);?></strong></td> 
	<td align="right"><strong><?php echo inr_format($fs_values['Total_expenses'][0]);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['Total_expenses'][2]);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['Total_expenses'][3]);?></strong></td>
</tr>
<tr>
        <td colspan="9"></td>
</tr>
<tr>
	<td align="center">3</td>
	<td align="Left"><strong>Loss before exceptional items and tax (1-2)</strong></td>
	<td align="center"></td>
	<td align="right"><strong><?php echo inr_format($fs_values['PBTEI'][4]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['PBTEI'][5]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['PBTEI'][6]*-1);?></strong></td> 
	<td align="right"><strong><?php echo inr_format($fs_values['PBTEI'][0]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['PBTEI'][2]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['PBTEI'][3]*-1);?></strong></td>
</tr><tr>
	<td align="center"><a href="fs_pl_note.php?pg_code=PL00500000" target="_blank">4</a></td>
	<td align="Left">Exceptional Items</td>
	<td align="center"></td>
	<td align="right"><?php echo inr_format($fs_values['PL00500000'][4]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00500000'][5]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00500000'][6]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00500000'][0]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00500000'][2]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00500000'][3]);?></td>
</tr><tr>
	<td align="center">5</td>
	<td align="Left"><strong>Loss/ Profit before tax (3+4)</strong></td>
	<td align="center"></td>
	<td align="right"><strong><?php echo inr_format($fs_values['PBT'][4]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['PBT'][5]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['PBT'][6]*-1);?></strong></td> 
	<td align="right"><strong><?php echo inr_format($fs_values['PBT'][0]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['PBT'][2]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['PBT'][3]*-1);?></strong></td>
</tr><tr>
	<td align="center">6</td>
	<td align="Left">Income tax expense</td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center"><a href="fs_pl_note.php?pg_code=PL00410000" target="_blank">a</a></td>
	<td align="Left">Current tax</td>
	<td align="center"></td>
	<td align="right"><?php echo inr_format($fs_values['PL00410000'][4]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00410000'][5]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00410000'][6]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00410000'][0]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00410000'][2]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00410000'][3]);?></td>
</tr>
<tr>
	<td align="center"><a href="fs_pl_note.php?pg_code=PL00415000" target="_blank">b</a></td>
	<td align="Left">b) Deferred tax</td>
	<td align="center"></td>
	<td align="right"><?php echo inr_format($fs_values['PL00415000'][4]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00415000'][5]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00415000'][6]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00415000'][0]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00415000'][2]);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00415000'][3]);?></td>
</tr>
<tr>
	<td align="center"> </td>
	<td align="Left">Total tax expense</td>
	<td align="center"></td>
 	<td align="right"><?php echo inr_format($fs_values['Tax_expense'][4]);?></td>
	<td align="right"><?php echo inr_format($fs_values['Tax_expense'][5]);?></td>
	<td align="right"><?php echo inr_format($fs_values['Tax_expense'][6]);?></td>
	<td align="right"><?php echo inr_format($fs_values['Tax_expense'][0]);?></td>
	<td align="right"><?php echo inr_format($fs_values['Tax_expense'][2]);?></td>
	<td align="right"><?php echo inr_format($fs_values['Tax_expense'][3]);?></td>
</tr>
<tr>
	<td align="center">7</td>
	<td align="Left"><strong>Loss/Profit after tax (5-6)</strong></td>
	<td align="center"></td>
 	<td align="right"><strong><?php echo inr_format($fs_values['PAT'][4]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['PAT'][5]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['PAT'][6]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['PAT'][0]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['PAT'][2]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['PAT'][3]*-1);?></strong></td>
</tr>
<tr>
	<td align="center">8</td>
	<td align="Left">Other comprehensive income, net of income tax Items that will not be reclassified to profit or loss</td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center"><a href="fs_pl_note.php?pg_code=PL00030000" target="_blank">a</a></td>
	<td align="Left">  - Remeasurements of post-employment benefit obligations (net of tax)</td>
	<td align="center"></td>
	<td align="right"><?php echo inr_format($fs_values['PL00030000'][4]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00030000'][5]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00030000'][6]*-1);?></td> 
	<td align="right"><?php echo inr_format($fs_values['PL00030000'][0]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00030000'][2]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00030000'][3]*-1);?></td>
</tr>
<tr>
	<td align="center"> </td>
	<td align="Left">Other comprehensive income for the period, net of tax</td>
	<td align="center"></td>
	<td align="right"><?php echo inr_format($fs_values['PL00030000'][4]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00030000'][5]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00030000'][6]*-1);?></td> 
	<td align="right"><?php echo inr_format($fs_values['PL00030000'][0]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00030000'][2]*-1);?></td>
	<td align="right"><?php echo inr_format($fs_values['PL00030000'][3]*-1);?></td>
</tr>
<tr>
	<td align="center">9</td>
	<td align="Left"><strong>Total comprehensive income for the period (7+8)</strong></td>
	<td align="center"></td>
 	<td align="right"><strong><?php echo inr_format($fs_values['TOCI'][4]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['TOCI'][5]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['TOCI'][6]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['TOCI'][0]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['TOCI'][2]*-1);?></strong></td>
	<td align="right"><strong><?php echo inr_format($fs_values['TOCI'][3]*-1);?></strong></td>
</tr>
<tr>
        <td colspan="9"></td>
</tr>
<tr>
	<td align="center"> </td>
	<td align="Left">Profit after tax is attributable to:</td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center"> </td>
	<td align="Left">  Owners of the Company</td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center"> </td>
	<td align="Left">   Non-controlling interest</td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center"> </td>
	<td align="Left">Other comprehensive income is attributable to:</td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center"> </td>
	<td align="Left">  Owners of the Company</td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center"> </td>
	<td align="Left">   Non-controlling interest</td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center"> </td>
	<td align="Left">Total comprehensive income attributable to:</td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center"> </td>
	<td align="Left">  Owners of the Company</td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center"> </td>
	<td align="Left">   Non-controlling interest</td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
        <td colspan="9"></td>
</tr>
<tr>
	<td align="center">10</td>
	<td align="Left"><strong>Paid-up equity share capital (Face Value of Rs. 10/- each)</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">11</td>
	<td align="Left"><strong>Paid-up debt capital/ outstanding long term debts</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">12</td>
	<td align="Left"><strong>9% non-cumulative preference shares (Face Value of Rs.  10/- each)</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">13</td>
	<td align="Left"><strong>Other equity excluding Revaluation Reserves</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">14</td>
	<td align="Left"><strong>Net Worth</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">15</td>
	<td align="Left"><strong>Earnings per share (INR)</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">  </td>
	<td align="Left">(Of Face Value of Rs. 10/- each) (not annualised)</td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">  </td>
	<td align="Left">(a) Basic</td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">  </td>
	<td align="Left">(b) Diluted</td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
        <td colspan="9"></td>
</tr>
<tr>
        <td colspan="9"><strong>The disclosure required as per the provisions of Regulation 52 (4) of SEBI (Listing Obligations and Disclosure Requirements) Regulations, 2015 is given below:</strong></td>
</tr>
<tr>
        <td colspan="9"></td>
</tr>
<tr>
	<td align="center">16</td>
	<td align="Left"><strong>Debt Equity Ratio</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">17</td>
	<td align="Left"><strong>Interest Service Coverage Ratio</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">18</td>
	<td align="Left"><strong>Debt Service Coverage Ratio</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">19</td>
	<td align="Left"><strong>Current ratio</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">20</td>
	<td align="Left"><strong>Long term debt to working capital</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">21</td>
	<td align="Left"><strong>Bad debts to Account receivable ratio</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">22</td>
	<td align="Left"><strong>Current liability ratio</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">23</td>
	<td align="Left"><strong>Total debts to total assets ratioNet Worth</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">24</td>
	<td align="Left"><strong>Debtors turnover</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">25</td>
	<td align="Left"><strong>Operating margin (%)</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">26</td>
	<td align="Left"><strong>Net profit margin (%)</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">27</td>
	<td align="Left"><strong>Capital redemption reserve</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tr>
	<td align="center">28</td>
	<td align="Left"><strong>Inventory Turnover ratio</strong></td>
	<td align="center"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
	<td align="right"></td>
</tr>
<tbody>
</body>
</html>
</div>
</body>