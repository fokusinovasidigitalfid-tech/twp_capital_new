@extends('template.layout2.base')
@section('content')
<style type="text/css">
    .toolbar {
        float: left;
        margin-bottom: 1em;
    }
</style>
<div class="nk-content-body">
    <div class="components-preview wide-md mx-auto">
        <div class="nk-block nk-block-lg">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h4 class="nk-block-title">Download Overtime</h4>
                </div>
            </div>
            <div class="card card-preview">
                <div class="card-inner">
                    <div id="form_page">
 
                        <div class="col-lg-12" width="100%">
            
                            <!-- <div class="form-group" id="form_page"> -->
                                <label><h2>Listing Overtime TWP</h2></label>
                            <div class="col-lg-12">
                                <P><small><?php echo $criteria?></small></P>   
                            </div><br> 
                               <table style="border: 1px solid #333" border="1" align="left" width="100%">
                                    <tr align="center"  style="background-color: #47789B;color: white;font-size: 16px;padding: 4px">
                                        <td>No.</td>
                                        <td>Name</td>
                                        <td>Lot No.</td>
                                        <td>Level</td>
                                        <td>Date</td>
                                        <td>From</td>
                                        <td>To</td>
                                        <td>Total Hours</td>
                                    </tr>
            
                                    <?php 
                                       if (empty($data)) {
                                           echo "<tr align='center' style='padding: 3px'>";
                                           echo "<td colspan='8'>";
                                            echo "No data available";
                                           echo "</td>";
                                           echo "</tr>";
                                       }
                                       $no=1;
                                    foreach ($data as $key) {
                                        
                                        $hourdiff = round((strtotime($key->end_overtime) - strtotime($key->start_overtime))/3600, 2);
                                        echo "<tr>";
                                         echo"<td align='center'>";
                                            echo $no.".";
                                        // echo $key->debtor_acct;
                                       echo" </td>";
                                        echo"<td style='padding-left:2px;'>";
                                            echo $key->name;
                                        // echo $key->debtor_acct;
                                       echo" </td>";
                                        echo" <td align='center'>";
                                            echo $key->lot_no;
                                        echo" </td>";
                                        echo" <td align='center'>";
                                            echo $key->level_no;
                                        echo" </td>";
                                        echo" <td align='center'>";
                                            echo Date('d/m/Y', strtotime($key->start_overtime));
                                       echo"  </td>";
                                        echo" <td align='center'>";
                                            echo Date('H:i', strtotime($key->start_overtime));
                                       echo"  </td>";
                                        echo" <td align='center'>";
                                            echo Date('H:i', strtotime($key->end_overtime));
                                       echo"  </td>";
                                       echo" <td align='center' style='padding-left:2px;'>";
                                            echo $hourdiff;
                                       echo"  </td>";
                                    echo" </tr>";
                                        $no++;
                                    }?>
                                    
                                    
                                </table>
                            <!-- </div> -->
            
                        </div>
                    </div>
                </div>
            </div><!-- .card-preview -->
        </div> <!-- nk-block -->
    </div>
</div>
@endsection
