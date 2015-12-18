<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Printer_model {


function print_receipt($id){
$struk = $this->menu_model->getStruks(array('where'=>array('pembayaran.id_order'=>$id)));
$conf = $this->office_model->get_config_resto();

require_once(dirname(__FILE__) . "/Escpos.php");

$fp = fopen("/dev/usb/lp1", "w");

$printer = new Escpos($fp);
$printer -> lineSpacing(19);
$printer ->setJustification(1);
$printer -> text($conf->row('nama_resto')."\n");
$printer -> text($conf->row('alamat_resto')."\n");
$printer -> text($conf->row('telephone').$hp."\n");
$printer ->setJustification();
$printer -> text("\n");
$printer -> text("No. Meja : ".$struk->row('no_meja')." || Nama : ".$struk->row('nama_konsumen')."\n");
$printer -> text("----------------------------------------");
$printer -> text("Nama Menu          Jml  Harga   SubTot\n");
$printer -> text("----------------------------------------");
foreach($struk->result() as $key => $v){	
	$sub_tot = $v->harga * $v->jumlah_selesai;
	$printer -> text($v->nama_menu." \n");
	$printer -> setJustification(2);
	$printer -> text($v->jumlah_selesai." | ".outNominal($v->harga)." | ".outNominal($sub_tot)." \n");
	$printer -> setJustification();
}
$printer -> text("         -------------------------------");
$printer -> text("                 TOTAL  : Rp.   ".outNominal($struk->row('total'))." \n");
$printer -> text("                 DISC   : %     ".$struk->row('discount')." \n");
$printer -> text("           GRAND TOTAL  : Rp.   ".outNominal($struk->row('grand_total'))." \n");
$printer -> text("                 TUNAI  : Rp.   ".outNominal($struk->row('fisik_uang'))." \n");
$printer -> text("                 KEMBALI: Rp.   ".outNominal($struk->row('kembali'))." \n");
$printer -> text("----------------------------------------");
$printer ->setJustification(1);
$printer -> text("Terimakasih atas kunjunganya.\n");
$printer ->setJustification();
$printer -> text("----------------------------------------");
$printer -> text("Kasir : ".$this->session->userdata('display_name')."       \n");
$printer -> text(date("d/m/Y H:i:s")."\n");
$printer -> text("\n");
$printer -> text("\n");
$printer -> cut();

}

}
