<?php
namespace fundsapp\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function _initialize(){
        if (I('session.userid')==null){
            $this->redirect('Login/login');
        }
    }

    public function index(){
        if (I('show')==1){
            $yearmonth=$_SESSION[yearmonth];
        }else{
        $yearmonth=I('yearmonth')==null?date("Y-m"):I('yearmonth');
        }
        //检查fundsdata中是否存在本月数据
        // $yearmonth=date("Y-m");
        $subcompany=I('session.subcompany');
        //如果子公司为集团的，取另外的tosubcompany参数
        if ($subcompany==0){
            $subcompany=I('tosubcompany');
        }
        // dump($subcompany);
        $where="(Fundsdata.subcompany=$subcompany) and Fundsdata.yearmonth='$yearmonth'";
        $count=M('Fundsdata')->where($where)->count();
        if ($count==0){
            //如果没有数据则创建本月度数据行
            //读取fundstitle,根据title在fundsdata创建数据行
            $m=M('Fundstitle');
            $t=$m->where("(subcompany=0 or subcompany=$subcompany) and valid=1")->order('showorder asc')->select();
            $d=M('Fundsdata');
            foreach ($t as $k=>$val){
                $data=array(
                    'yearmonth'=>$yearmonth,
                    'subcompany'=>$subcompany,
                    'titleid'=>$val['id'],
                );
                $d->data($data)->add();
                
            }
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

        // 计算隐藏的日期
        $v=M()->query(" call calday('$yearmonth')")[0];
        $lastday=$v['dd'];
        $curyearmonth=$v['curyearmonth'];
        $hideday=(int)substr($lastday,8);

        if ($curyearmonth==substr($lastday,0,7)){
            $hideday=$hideday+1;
        }

        $d=M('Fundsdata');
        $d->join('Fundstitle on Fundsdata.titleid=Fundstitle.id');
        $data=$d->where($where)->order('fundstitle.showorder asc')->select();
        $this->data=$data;

        $d=M('Fundsdata');
        $this->yearmonthlist=$d->field('yearmonth')->group('yearmonth')->order("yearmonth desc")->select();

        $_SESSION['yearmonth']=$yearmonth;
        $this->yearmonth=$yearmonth;

        $this->memo=$memo;
        $this->hideday=$hideday;
        $this->display("main");
    }

    public function modifydata(){
        $yearmonth=I('get.yearmonth');
        $subcompany=I('session.subcompany');
        $day=I('get.day');
        $order="fundstitle.showorder asc";

        $where=array(
            'Fundsdata.yearmonth'=>$yearmonth,
            'Fundsdata.subcompany'=>$subcompany,
            'Fundstitle.isdata'=>1,
        );

        $memo="";
        $fileName = "memo/$subcompany-$yearmonth.memo"; 
        $handle = fopen($fileName, "r"); 
        if ($handle==true){
            while (!feof($handle)){ 
                $memo = $memo.fgets($handle); 
            } 
        }

        $d=D('Fundsdata');
        $d->join('Fundstitle on Fundsdata.titleid=Fundstitle.id');
        $data1=$d->where($where)->field("fundsdata.titleid,fundstitle.category,fundsdata.id,titlename,day".$day." as day")->order($order)->limit(0,17)->select();

        $d=D('Fundsdata');
        $d->join('Fundstitle on Fundsdata.titleid=Fundstitle.id');
        $data2=$d->where($where)->field("fundsdata.titleid,fundstitle.category,fundsdata.id,titlename,day".$day." as day")->order($order)->limit(17,17)->select();

        $d=D('Fundsdata');
        $d->join('Fundstitle on Fundsdata.titleid=Fundstitle.id');
        $data3=$d->where($where)->field("fundsdata.titleid,fundstitle.category,fundsdata.id,titlename,day".$day." as day")->order($order)->limit(34,17)->select();

        $this->yearmonth=$yearmonth;
        $this->day=$day;
        $this->data1=$data1;
        $this->data2=$data2;
        $this->data3=$data3;
        $this->memo=$memo;
        $this->display();
    }

    public function ajaxdata(){
        $id=I('get.id');
        $val=I('get.val');
        $day="day".I('get.day');
        $d=D('Fundsdata');
        $data=array(
            "{$day}"=>"{$val}",
        );
        $result=$d->data($data)->where("id={$id}")->save();
        //本项目月累计
        // $sql="UPDATE fundsdata set sum_month=day1+day2+day3+day4+day5+day6+day7+day8+day9+day10+day11+day12+day13+day14+day15+day16+day17+day18+day19+day20+day21+day22+day23+day24+day25+day26+day27+day28+day29+day30+day31 where id=$id;";
        // $result=$result+M('')->execute($sql);
        if ($result==1){
            echo $id;
        }else{
            echo -1;
        }
    }

    public function domodifydata(){
        $subcompany=I('get.subcompany');
        $yearmonth=I('get.yearmonth');
        $handle = fopen("memo/$subcompany-$yearmonth.memo", "w"); 
        $text = I('memo'); 
        if(fwrite($handle, $text) == FALSE){ 
            $_SESSION['error'] = '<span class="redtxt">There was an error</span>'; 
            }else{ 
            $_SESSION['error'] = '<span class="redtxt">File edited successfully</span>'; 
        } 
        fclose($handle);

        //各项累计
        M()->execute(" call calculate('$yearmonth',$subcompany)");
         $this->redirect('index?show=1');
    }

    public function chgmainshowall(){
        $_SESSION['mainshowall']=1;
        $this->redirect('index?show=1');
    }


    public function chgmainnotshowall(){
        $_SESSION['mainshowall']=0;
        $this->redirect('index?show=1');
    }


    public function exportexcel(){
        import("Org.Util.PHPExcel");
        $objPHPExcel=new \PHPExcel();

        $objPHPExcel->getProperties()->setCreator("Lao")
                                     ->setLastModifiedBy("Lao")
                                     ->setTitle("资金表")
                                     ->setSubject("资金表")
                                     ->setDescription("资金表")
                                     ->setKeywords("资金表")
                                     ->setCategory("资金表");
        $yearmonth=$_GET['yearmonth'];
        $subcompany=I('session.subcompany');

        $fileds="";
        for ($i=31;$i>=1;$i--){
            $fileds.=",day".$i;
        }
        // echo $fields;
        $where="(Fundsdata.subcompany=$subcompany) and Fundsdata.yearmonth='$yearmonth'";
        $d=M('Fundsdata');
        $d->field("category,concat(titlename,titleid) titlename,day0,sum_month".$fileds)->join('Fundstitle on Fundsdata.titleid=Fundstitle.id');
        $data=$d->where($where)->order('fundstitle.showorder asc')->select();

                               
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue("A2","类别");
        $objPHPExcel->getActiveSheet()->setCellValue("B2","项目");
        $objPHPExcel->getActiveSheet()->setCellValue("C2","预测数");
        $objPHPExcel->getActiveSheet()->setCellValue("D2","月度累计");
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true); 
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true); 

        $v=M()->query(" call calday('$yearmonth')")[0];
        $lastday=$v['dd'];
        $curyearmonth=$v['curyearmonth'];
        $hideday=(int)substr($lastday,8);
        $aa=substr($lastday,0,7);
        if ($curyearmonth==substr($lastday,0,7)){
            $hideday=$hideday+1;
        }
        for ($c=4,$i=31;$c<35;$c++,$i--){
            $day=$i."日";
            $h=\PHPExcel_Cell::stringFromColumnIndex($c);
            $colum = $h."2";
            $objPHPExcel->getActiveSheet()->getColumnDimension($h)->setAutoSize(true);      
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

        if ($_SESSION[subcompany]==1){
            $ss="南部 ".$ss;
        }else if ($_SESSION[subcompany]==2){
            $ss="北部 ".$ss;
        }else if ($_SESSION[subcompany]==3){
            $ss="中部 ".$ss;
        }else if ($_SESSION[subcompany]==4){
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
        
        $objPHPExcel->getActiveSheet()->setCellValue("A1", $ss);
        //写入备注
        $objPHPExcel->getActiveSheet()->setCellValue("A67", "备注");
        $objPHPExcel->getActiveSheet()->setCellValue("A68", $memo);
        $objPHPExcel->getActiveSheet()->getStyle('A68')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->getRowDimension(68)->setRowHeight(100);
        $objPHPExcel->getActiveSheet()->getStyle('A68')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_TOP);
        //合并单元格，并且居中显示
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

        $objPHPExcel->getActiveSheet()->setTitle('资金表');

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);


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
        $font_size=9;
        $objPHPExcel->getActiveSheet()->getStyle('A3:AI68')->getFont()->setSize($font_size);

        import("Org.Util.PHPExcel.IOFactory");
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $filename="".$subcompany."-".$yearmonth.".xlsx";
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