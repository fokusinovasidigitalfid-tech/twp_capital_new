<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>IFCA Software</title>

<style>

@page{
    size: A4 landscape;
    margin: 10mm 10mm 22mm 10mm;
}

body{
    font-family: DejaVu Sans, sans-serif;
    font-size:10px;
    margin:0;
    padding:0;
}

.invoice{
    width:100%;
}

h4{
    margin:0;
    font-size:16px;
    font-weight:bold;
}

hr{
    margin:8px 0 12px;
    border:0;
    border-top:1px solid #000;
}

table{
    width:100%;
    border-collapse:collapse;
    table-layout:fixed;
    margin-bottom:15mm;
}

thead{
    display:table-header-group;
}

tfoot{
    display:table-footer-group;
}

tbody tr{
    page-break-inside:avoid;
}

th,
td{
    border:1px solid #000;
    padding:5px;
    vertical-align:top;
    word-break:break-word;
}

th{
    background:#FFA500;
    text-align:center;
    font-weight:bold;
}

/* Lebar kolom */
.col-no{width:5%;}
.col-date{width:35%;}
.col-user{width:40%;}
.col-from{width:20%;}

.footer{
    position:fixed;
    left:0;
    right:0;
    bottom:-10mm;
    border-top:1px solid #999;
    padding-top:3px;
    text-align:center;
    font-size:8px;
}

</style>

</head>

<body>

<!-- PINDAHKAN FOOTER KE ATAS -->
<div class="footer">
    Capital Place Tenant Web Portal may contain information that is created and managed by various sources, both internal and external. At no time shall Capital Place Building Management be responsible or liable, directly or indirectly, for any damage or loss resulting from or alleged to result from the use of or reliance on any such content in Capital Place Tenant Web Portal.
</div>

<div class="invoice">

    <h4>History User Login</h4>

    <hr>

    <table>

        <thead>
            <tr>
                <th class="col-no">#</th>
                <th class="col-date">Login Date</th>
                <th class="col-user">User Name</th>
                <th class="col-from">Login From</th>
            </tr>
        </thead>

        <tbody>

        <?php if(!empty($listD)): ?>
            <?php echo $listD; ?>

        <?php else: ?>
            <tr>
                <td colspan="4" align="center">
                    Data Not Available
                </td>
            </tr>
        <?php endif; ?>

        </tbody>

        <tfoot>
            <tr>
                <td colspan="4" style="border:none;padding:0;"></td>
            </tr>
        </tfoot>

    </table>

</div>

</body>
</html><?php /**PATH E:\xampp\htdocs\twp\webadmin\resources\views/history/explog.blade.php ENDPATH**/ ?>