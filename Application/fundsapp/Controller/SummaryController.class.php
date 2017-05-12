<?php
namespace fundsapp\Controller;
use Think\Controller;
class SummaryController extends Controller {

    public function _initialize(){
        if (I('session.userid')==null){
            $this->redirect('Login/login');
        }
    }

    public function index(){
        //检查fundsdata中是否存在本月数据
        $yearmonth=I('yearmonth')==null?date("Y-m"):I('yearmonth');
   
        $d=M('Fundsdata');
        $this->yearmonthlist=$d->field('yearmonth')->group('yearmonth')->order("yearmonth desc")->select();
        $this->yearmonth=$yearmonth;
        $lastday=M()->query(" call calday('$yearmonth')")[0]['dd'];
        $this->lastday=$lastday;
        $this->datalist=M()->query("call summary('$yearmonth','$lastday')");
        $this->display("main");
    }

    public function exportexcel(){
        import("Org.Util.PHPExcel");
        $objPHPExcel=new \PHPExcel();

        $objPHPExcel->getProperties()->setCreator("LaoHaiqing")
                                     ->setLastModifiedBy("LaoHaiqing")
                                     ->setTitle("funds")
                                     ->setSubject("funds")
                                     ->setDescription("Test document for PHPExcel, generated using PHP classes.")
                                     ->setKeywords("office PHPExcel php")
                                     ->setCategory("Test result file");
        $yearmonth=$_GET['yearmonth'];

        //====集团总表======
        $lastday=M()->query(" call calday('$yearmonth')")[0]['dd'];
        $data=M()->query("call summary('$yearmonth','$lastday')");
        
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->setTitle('资金汇总表');
        $objPHPExcel->getActiveSheet()->setCellValue("A1", "资金汇总表-$yearmonth 单位：万元");
        $objPHPExcel->getActiveSheet()->freezePane('C4');
        $objPHPExcel->getActiveSheet()->setCellValue("A2","类别");
        $objPHPExcel->getActiveSheet()->mergeCells('A2:A3');
        $objPHPExcel->getActiveSheet()->setCellValue("B2","项目");
        $objPHPExcel->getActiveSheet()->mergeCells('B2:B3');
        $objPHPExcel->getActiveSheet()->setCellValue("C2","月度($yearmonth)累计");
        $objPHPExcel->getActiveSheet()->mergeCells('C2:G2');
        $objPHPExcel->getActiveSheet()->setCellValue("H2","$lastday");
        $objPHPExcel->getActiveSheet()->mergeCells('H2:L2');
        $objPHPExcel->getActiveSheet()->setCellValue("M2","预测数");
        $objPHPExcel->getActiveSheet()->mergeCells('M2:Q2');
        $objPHPExcel->getActiveSheet()->setCellValue("R2","本年累计");
        $objPHPExcel->getActiveSheet()->mergeCells('R2:V2');
        $objPHPExcel->getActiveSheet()->setCellValue("W2","去年同月实际");
        $objPHPExcel->getActiveSheet()->mergeCells('W2:AA2');

        $objPHPExcel->getActiveSheet()->setCellValue("C3","合计");
        $objPHPExcel->getActiveSheet()->setCellValue("D3","南部");
        $objPHPExcel->getActiveSheet()->setCellValue("E3","北部");
        $objPHPExcel->getActiveSheet()->setCellValue("F3","中部");
        $objPHPExcel->getActiveSheet()->setCellValue("G3","玫瑰");
        $objPHPExcel->getActiveSheet()->setCellValue("H3","合计");
        $objPHPExcel->getActiveSheet()->setCellValue("I3","南部");
        $objPHPExcel->getActiveSheet()->setCellValue("J3","北部");
        $objPHPExcel->getActiveSheet()->setCellValue("K3","中部");
        $objPHPExcel->getActiveSheet()->setCellValue("L3","玫瑰");
        $objPHPExcel->getActiveSheet()->setCellValue("M3","合计");
        $objPHPExcel->getActiveSheet()->setCellValue("N3","南部");
        $objPHPExcel->getActiveSheet()->setCellValue("O3","北部");
        $objPHPExcel->getActiveSheet()->setCellValue("P3","中部");
        $objPHPExcel->getActiveSheet()->setCellValue("Q3","玫瑰");
        $objPHPExcel->getActiveSheet()->setCellValue("R3","合计");
        $objPHPExcel->getActiveSheet()->setCellValue("S3","南部");
        $objPHPExcel->getActiveSheet()->setCellValue("T3","北部");
        $objPHPExcel->getActiveSheet()->setCellValue("U3","中部");
        $objPHPExcel->getActiveSheet()->setCellValue("V3","玫瑰");
        $objPHPExcel->getActiveSheet()->setCellValue("W3","合计");
        $objPHPExcel->getActiveSheet()->setCellValue("X3","南部");
        $objPHPExcel->getActiveSheet()->setCellValue("Y3","北部");
        $objPHPExcel->getActiveSheet()->setCellValue("Z3","中部");
        $objPHPExcel->getActiveSheet()->setCellValue("AA3","玫瑰");

        //输出数据
        $r=4;
        foreach($data as $key1=>$val1){
            $c=0;
            foreach($val1 as $key2 =>$val2){
                $colum = \PHPExcel_Cell::stringFromColumnIndex($c);
                $cell=$colum.$r;

                if ($val2!=0 || $c<2){
                    $objPHPExcel->getActiveSheet()->setCellValue($cell, $val2);
                    $objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                }
                $c++;
            }
            $r++;
        }

        //合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A4:A7');
        $objPHPExcel->getActiveSheet()->mergeCells('A8:A10');
        $objPHPExcel->getActiveSheet()->mergeCells('A11:A14');
        $objPHPExcel->getActiveSheet()->mergeCells('A15:A27');
        $objPHPExcel->getActiveSheet()->mergeCells('A28:A32');
        $objPHPExcel->getActiveSheet()->mergeCells('A33:A41');
        $objPHPExcel->getActiveSheet()->mergeCells('A42:A51');
        $objPHPExcel->getActiveSheet()->mergeCells('A53:A62');
        $objPHPExcel->getActiveSheet()->mergeCells('A65:A67');

        //对齐方式
        $objPHPExcel->getActiveSheet()->getStyle('C2:AA2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C3:AA3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A4:A67')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A4:A67')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        //设置背景
        $objPHPExcel->getActiveSheet()->getStyle('C4:G67')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('C4:G67')->getFill()->getStartColor()->setARGB('DDDDFF');

        $objPHPExcel->getActiveSheet()->getStyle('H4:L67')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('H4:L67')->getFill()->getStartColor()->setARGB('D7FFEE');

        $objPHPExcel->getActiveSheet()->getStyle('M4:Q67')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('M4:Q67')->getFill()->getStartColor()->setARGB('FFE4CA');

        $objPHPExcel->getActiveSheet()->getStyle('R4:V67')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('R4:V67')->getFill()->getStartColor()->setARGB('D8D8EB');

        $objPHPExcel->getActiveSheet()->getStyle('W4:AA67')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('W4:AA67')->getFill()->getStartColor()->setARGB('FFD0FF');

        //设置粗体
        $objPHPExcel->getActiveSheet()->getStyle('C3:C67')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('H3:H67')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('M3:M67')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('R3:R67')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('W3:W67')->getFont()->setBold(true);

        //设置宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);

        //画边框
        $styleThinBlackBorderOutline = array('borders' => array (
             'allborders' => array (
                   'style' => \PHPExcel_Style_Border::BORDER_THIN,   //设置border样式
                   //'style' => PHPExcel_Style_Border::BORDER_THICK,  另一种样式
                   'color' => array ('argb' => 'FF000000'),          //设置border颜色
                ),
            ),
        );

        $font_size=10;
        $objPHPExcel->getActiveSheet()->getStyle('A2:AA67')->applyFromArray($styleThinBlackBorderOutline);
        $objPHPExcel->getActiveSheet()->getStyle('A3:AI66')->getFont()->setSize($font_size);
        $lastday=(int)substr(M()->query(" call calday('$yearmonth')")[0]['dd'],8);

        // 计算隐藏的日期
        $v=M()->query(" call calday('$yearmonth')")[0];
        $lastday=$v['dd'];
        $curyearmonth=$v['curyearmonth'];
        $hideday=(int)substr($lastday,8);
        $aa=substr($lastday,0,7);
        if ($curyearmonth==substr($lastday,0,7)){
            $hideday=$hideday+1;
        }


        //======三四公司地各项合计====
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex(1)->setTitle('月度合计');
        $data=M()->query(" call sum_all('$yearmonth')");

        $objPHPExcel->getActiveSheet()->setCellValue("A2","类别");
            $objPHPExcel->getActiveSheet()->setCellValue("B2","项目");
            $objPHPExcel->getActiveSheet()->setCellValue("C2","预测数");
            $objPHPExcel->getActiveSheet()->setCellValue("D2","月度累计");
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true); 
            
            for ($c=4,$i=31;$c<35;$c++,$i--){
                //标题
                $day=$i."日";
                $h=\PHPExcel_Cell::stringFromColumnIndex($c);
                //拼出“C2”
                $colum = $h."2";
                $objPHPExcel->getActiveSheet()->getColumnDimension($h)->setAutoSize(true);      
                $objPHPExcel->getActiveSheet()->getColumnDimension($h)->setWidth(2); 
                $objPHPExcel->getActiveSheet()->setCellValue($colum,$day);
                $objPHPExcel->getActiveSheet()->getStyle($colum)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                if ($hideday<$i){
                    $objPHPExcel->getActiveSheet()->getColumnDimension($h)->setVisible(false); 
                }
            }

            $r=3;
            foreach($data as $key1=>$val1){
                $c=0;
                foreach($val1 as $key2 =>$val2){
                    $colum = \PHPExcel_Cell::stringFromColumnIndex($c);
                    $cell=$colum.$r;

                    if ($val2!=0 || $c<2){
                        $objPHPExcel->getActiveSheet()->setCellValue($cell, $val2);
                        $objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    }
                    $c++;
                }
                $r++;
            }

            $ss="月度合计-$yearmonth 单位：元";
            
            //合并单元格，并且居中显示
            $objPHPExcel->getActiveSheet()->setCellValue("A1", $ss);
            //  //写入备注
            // $objPHPExcel->getActiveSheet()->setCellValue("A67", "备注");
            // $objPHPExcel->getActiveSheet()->setCellValue("A68", $memo);

            // $objPHPExcel->getActiveSheet()->getStyle('A68')->getAlignment()->setWrapText(true);
            // $objPHPExcel->getActiveSheet()->getRowDimension(68)->setRowHeight(100);
            // $objPHPExcel->getActiveSheet()->getStyle('A68')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);


            $objPHPExcel->getActiveSheet()->freezePane('E3');
            $objPHPExcel->getActiveSheet()->mergeCells('A3:A6');
            $objPHPExcel->getActiveSheet()->mergeCells('A7:A9');
            $objPHPExcel->getActiveSheet()->mergeCells('A10:A13');
            $objPHPExcel->getActiveSheet()->mergeCells('A14:A26');
            $objPHPExcel->getActiveSheet()->mergeCells('A27:A31');
            $objPHPExcel->getActiveSheet()->mergeCells('A32:A40');
            $objPHPExcel->getActiveSheet()->mergeCells('A41:A51');
            $objPHPExcel->getActiveSheet()->mergeCells('A52:A61');
            $objPHPExcel->getActiveSheet()->mergeCells('A64:A66');

            $objPHPExcel->getActiveSheet()->getStyle('A3:A64')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            
            //高亮小计、合计
            $objPHPExcel->getActiveSheet()->getStyle('B9:AI9')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B9:AI9')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('B13:AI13')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B13:AI13')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('B26:AI26')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B26:AI26')->getFill()->getStartColor()->setARGB('FF98FB98');        
            $objPHPExcel->getActiveSheet()->getStyle('B31:AI31')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B31:AI31')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('B40:AI40')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B40:AI40')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('B50:AI50')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B50:AI50')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('B51:AI51')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B51:AI51')->getFill()->getStartColor()->setARGB('FF98FB98');        
            $objPHPExcel->getActiveSheet()->getStyle('B61:AI61')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B61:AI61')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('B63:AI63')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B63:AI63')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('B64:AI64')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B64:AI64')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('B65:AI65')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B65:AI65')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('A66:AI66')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B66:AI66')->getFill()->getStartColor()->setARGB('FF98FB98');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);

            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);


            $styleThinBlackBorderOutline = array('borders' => array (
                'allborders' => array (
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,   //设置border样式
                    //'style' => PHPExcel_Style_Border::BORDER_THICK,  另一种样式
                    'color' => array ('argb' => 'FF000000'),          //设置border颜色
                    ),
                ),
            );

            //画网格线
            $objPHPExcel->getActiveSheet()->getStyle('A3:AI66')->applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel->getActiveSheet()->getStyle('A3:AI66')->getFont()->setSize($font_size);


        //====各地分表4个======

        for ($s=1;$s<=4;$s++){
            $objPHPExcel->createSheet();
        }

        $objPHPExcel->setActiveSheetIndex(2)->setTitle('南部');
        $objPHPExcel->setActiveSheetIndex(3)->setTitle('北部');
        $objPHPExcel->setActiveSheetIndex(4)->setTitle('中部');
        $objPHPExcel->setActiveSheetIndex(5)->setTitle('玫瑰');

        //======单表==========
        for ($k=2;$k<=5;$k++){
            $subcompany=$k-1;
            $fileds="";
            for ($i=31;$i>=1;$i--){
                $fileds.=",day".$i;
            }

            $where="(Fundsdata.subcompany=$subcompany) and Fundsdata.yearmonth='$yearmonth'";
            $d=M('Fundsdata');
            $d->field("category,concat(titlename,titleid) titlename,day0,sum_month".$fileds)->join('Fundstitle on Fundsdata.titleid=Fundstitle.id');
            $data=$d->where($where)->order('fundstitle.showorder asc')->select();

                                
            $objPHPExcel->setActiveSheetIndex($k);
            $objPHPExcel->getActiveSheet()->setCellValue("A2","类别");
            $objPHPExcel->getActiveSheet()->setCellValue("B2","项目");
            $objPHPExcel->getActiveSheet()->setCellValue("C2","预测数");
            $objPHPExcel->getActiveSheet()->setCellValue("D2","月度累计");
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true); 
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true); 
            
            
            for ($c=4,$i=31;$c<35;$c++,$i--){
                //标题
                $day=$i."日";
                $h=\PHPExcel_Cell::stringFromColumnIndex($c);
                //拼出“C2”
                $colum = $h."2";
                $objPHPExcel->getActiveSheet()->getColumnDimension($h)->setAutoSize(true);      
                $objPHPExcel->getActiveSheet()->getColumnDimension($h)->setWidth(2); 
                $objPHPExcel->getActiveSheet()->setCellValue($colum,$day);
                $objPHPExcel->getActiveSheet()->getStyle($colum)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                if ($hideday<$i){
                    $objPHPExcel->getActiveSheet()->getColumnDimension($h)->setVisible(false); 
                }
            }

            $r=3;
            foreach($data as $key1=>$val1){
                $c=0;
                foreach($val1 as $key2 =>$val2){
                    $colum = \PHPExcel_Cell::stringFromColumnIndex($c);
                    $cell=$colum.$r;

                    if ($val2!=0 || $c<2){
                        $objPHPExcel->getActiveSheet()->setCellValue($cell, $val2);
                        $objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    }
                    $c++;
                }
                $r++;
            }

            $ss="资金表-$yearmonth 单位：元";

            if ($subcompany==1){
                $ss="南部 ".$ss;
            }else if ($subcompany==2){
                $ss="北部 ".$ss;
            }else if ($subcompany==3){
                $ss="中部 ".$ss;
            }else if ($subcompany==4){
                $ss="玫瑰 ".$ss;
            }

            //读取备注文件
            $memo="";
            $fileName = "memo/$subcompany-$yearmonth.memo"; 
            $handle = fopen($fileName, "r"); 
            if ($handle==true){
                while (!feof($handle)){ 
                    $memo = $memo.fgets($handle); 
                } 
            }
            // $subcompany=$subcompany+1;;
            //合并单元格，并且居中显示
            $objPHPExcel->getActiveSheet()->setCellValue("A1", $ss);
             //写入备注
            $objPHPExcel->getActiveSheet()->setCellValue("A67", "备注");
            $objPHPExcel->getActiveSheet()->setCellValue("A68", $memo);
            $objPHPExcel->getActiveSheet()->getStyle('A68')->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getRowDimension(68)->setRowHeight(100);
            $objPHPExcel->getActiveSheet()->getStyle('A68')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);


            $objPHPExcel->getActiveSheet()->freezePane('E3');
            $objPHPExcel->getActiveSheet()->mergeCells('A3:A6');
            $objPHPExcel->getActiveSheet()->mergeCells('A7:A9');
            $objPHPExcel->getActiveSheet()->mergeCells('A10:A13');
            $objPHPExcel->getActiveSheet()->mergeCells('A14:A26');
            $objPHPExcel->getActiveSheet()->mergeCells('A27:A31');
            $objPHPExcel->getActiveSheet()->mergeCells('A32:A40');
            $objPHPExcel->getActiveSheet()->mergeCells('A41:A51');
            $objPHPExcel->getActiveSheet()->mergeCells('A52:A61');
            $objPHPExcel->getActiveSheet()->mergeCells('A64:A66');
            $objPHPExcel->getActiveSheet()->mergeCells('A67:AI67');
            $objPHPExcel->getActiveSheet()->mergeCells('A68:AI68');

            $objPHPExcel->getActiveSheet()->getStyle('A3:A64')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            
            //高亮小计、合计
            $objPHPExcel->getActiveSheet()->getStyle('B9:AI9')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B9:AI9')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('B13:AI13')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B13:AI13')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('B26:AI26')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B26:AI26')->getFill()->getStartColor()->setARGB('FF98FB98');        
            $objPHPExcel->getActiveSheet()->getStyle('B31:AI31')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B31:AI31')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('B40:AI40')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B40:AI40')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('B50:AI50')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B50:AI50')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('B51:AI51')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B51:AI51')->getFill()->getStartColor()->setARGB('FF98FB98');        
            $objPHPExcel->getActiveSheet()->getStyle('B61:AI61')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B61:AI61')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('B63:AI63')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B63:AI63')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('B64:AI64')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B64:AI64')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('B65:AI65')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B65:AI65')->getFill()->getStartColor()->setARGB('FF98FB98');
            $objPHPExcel->getActiveSheet()->getStyle('A66:AI66')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle('B66:AI66')->getFill()->getStartColor()->setARGB('FF98FB98');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);

            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);


            $styleThinBlackBorderOutline = array('borders' => array (
                'allborders' => array (
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,   //设置border样式
                    //'style' => PHPExcel_Style_Border::BORDER_THICK,  另一种样式
                    'color' => array ('argb' => 'FF000000'),          //设置border颜色
                    ),
                ),
            );

            //画网格线
            $objPHPExcel->getActiveSheet()->getStyle('A3:AI68')->applyFromArray($styleThinBlackBorderOutline);
            $objPHPExcel->getActiveSheet()->getStyle('A3:AI68')->getFont()->setSize($font_size);
        }
        $objPHPExcel->setActiveSheetIndex(0);

//=================
        import("Org.Util.PHPExcel.IOFactory");
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $filename="资金汇总-$yearmonth..xlsx";
        header("Pragma: public");
         header("Expires: 0");
         header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
         header("Content-Type:application/force-download");
         header("Content-Type:application/vnd.ms-execl");
         header("Content-Type:application/octet-stream");
         header("Content-Type:application/download");
         header("Content-Disposition:attachment;filename=$filename");
         header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        $objWriter=null;
    }
}