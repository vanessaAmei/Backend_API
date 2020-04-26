<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class laporan_model extends CI_Model
{
    public function getPendapatanProduk($tahun) {
        $query = "SELECT  IFNULL(SUM(IF( MONTH(tanggal) = 01, total_harga, 0)),0) AS januari , 
        IFNULL(SUM(IF( MONTH(tanggal) = 02, total_harga, 0)),0) AS februari, 
        IFNULL(SUM(IF( MONTH(tanggal) = 03, total_harga, 0)),0) AS maret, 
        IFNULL(SUM(IF( MONTH(tanggal) = 04, total_harga, 0)),0) AS april, 
        IFNULL(SUM(IF( MONTH(tanggal) = 05, total_harga, 0)),0) AS mei, 
        IFNULL(SUM(IF( MONTH(tanggal) = 06, total_harga, 0)),0) AS juni, 
        IFNULL(SUM(IF( MONTH(tanggal) = 07, total_harga, 0)),0) AS july, 
        IFNULL(SUM(IF( MONTH(tanggal) = 08, total_harga, 0)),0) AS agustus, 
        IFNULL(SUM(IF( MONTH(tanggal) = 09, total_harga, 0)),0) AS september, 
        IFNULL(SUM(IF( MONTH(tanggal) = 10, total_harga, 0)),0) AS oktober, 
        IFNULL(SUM(IF( MONTH(tanggal) = 11, total_harga, 0)),0) AS november, 
        IFNULL(SUM(IF( MONTH(tanggal) = 12, total_harga, 0)),0) AS desember, 
        IFNULL(SUM(total_harga),0) AS total FROM transaksi_produk WHERE status='Selesai' AND YEAR(tanggal)='$tahun'";
        $result = $this->db->query($query);
        return $result->result();    
    }

    public function getPendapatanLayanan($tahun) {
        $query = "SELECT  IFNULL(SUM(IF( MONTH(tanggal) = 01, total_harga, 0)),0) AS januari , 
        IFNULL(SUM(IF( MONTH(tanggal) = 02, total_harga, 0)),0) AS februari, 
        IFNULL(SUM(IF( MONTH(tanggal) = 03, total_harga, 0)),0) AS maret, 
        IFNULL(SUM(IF( MONTH(tanggal) = 04, total_harga, 0)),0) AS april, 
        IFNULL(SUM(IF( MONTH(tanggal) = 05, total_harga, 0)),0) AS mei, 
        IFNULL(SUM(IF( MONTH(tanggal) = 06, total_harga, 0)),0) AS juni, 
        IFNULL(SUM(IF( MONTH(tanggal) = 07, total_harga, 0)),0) AS july, 
        IFNULL(SUM(IF( MONTH(tanggal) = 08, total_harga, 0)),0) AS agustus, 
        IFNULL(SUM(IF( MONTH(tanggal) = 09, total_harga, 0)),0) AS september, 
        IFNULL(SUM(IF( MONTH(tanggal) = 10, total_harga, 0)),0) AS oktober, 
        IFNULL(SUM(IF( MONTH(tanggal) = 11, total_harga, 0)),0) AS november, 
        IFNULL(SUM(IF( MONTH(tanggal) = 12, total_harga, 0)),0) AS desember,
        IFNULL(SUM(total_harga),0) AS total FROM transaksi_layanan WHERE status='Selesai' AND YEAR(tanggal)='$tahun'";
        $result = $this->db->query($query);
        return $result->result();     
    }

    public function produkTerlaris($bulan, $tahun) {
        $query = "SELECT IFNULL(IF( MONTH(tanggal) = $bulan AND YEAR(c.tanggal)='$tahun' , a.nama, '-'),0) AS nama, IFNULL(SUM(IF( MONTH(tanggal) = $bulan AND YEAR(c.tanggal)='$tahun', b.jumlah, 0)),0) AS jumlah 
        FROM detail_tp b 
        JOIN produk a ON a.id_produk=b.id_produk 
        JOIN transaksi_produk c ON b.id_tp=c.id_tp 
        WHERE c.status='Selesai' GROUP BY a.nama ORDER BY b.jumlah ASC LIMIT 1 ";
        $result = $this->db->query($query);
        return $result->result();     
    }

    public function pendapatanProdukBulanan($bulan, $tahun) {
        $query = "SELECT IFNULL(IF( MONTH(tanggal) = $bulan AND YEAR(c.tanggal)='$tahun' , a.nama, '-'),0) AS nama, IFNULL(SUM(IF( MONTH(tanggal) = $bulan AND YEAR(c.tanggal)='$tahun', b.jumlah, 0)),0) AS jumlah 
        FROM detail_tp b 
        JOIN produk a ON a.id_produk=b.id_produk 
        JOIN transaksi_produk c ON b.id_tp=c.id_tp 
        WHERE c.status='Selesai' GROUP BY a.nama";
        $result = $this->db->query($query);
        return $result->result();     
    }
    
    public function pengadaanTahunan($tahun) {
        $query = "SELECT  IFNULL(SUM(IF( MONTH(tanggal) = 01, total_harga, 0)),0) AS januari , 
        IFNULL(SUM(IF( MONTH(tanggal) = 02, total_harga, 0)),0) AS februari, 
        IFNULL(SUM(IF( MONTH(tanggal) = 03, total_harga, 0)),0) AS maret, 
        IFNULL(SUM(IF( MONTH(tanggal) = 04, total_harga, 0)),0) AS april, 
        IFNULL(SUM(IF( MONTH(tanggal) = 05, total_harga, 0)),0) AS mei, 
        IFNULL(SUM(IF( MONTH(tanggal) = 06, total_harga, 0)),0) AS juni, 
        IFNULL(SUM(IF( MONTH(tanggal) = 07, total_harga, 0)),0) AS july, 
        IFNULL(SUM(IF( MONTH(tanggal) = 08, total_harga, 0)),0) AS agustus, 
        IFNULL(SUM(IF( MONTH(tanggal) = 09, total_harga, 0)),0) AS september, 
        IFNULL(SUM(IF( MONTH(tanggal) = 10, total_harga, 0)),0) AS oktober, 
        IFNULL(SUM(IF( MONTH(tanggal) = 11, total_harga, 0)),0) AS november, 
        IFNULL(SUM(IF( MONTH(tanggal) = 12, total_harga, 0)),0) AS desember, 
        IFNULL(SUM(total_harga),0) AS total FROM pengadaan WHERE status='Selesai' AND YEAR(tanggal)='$tahun'";
        $result = $this->db->query($query);
        return $result->result();    
    }

    public function pengadaanBulanan($bulan, $tahun) {
        $query = "SELECT IFNULL(IF( MONTH(tanggal) = $bulan AND YEAR(c.tanggal)='$tahun' , a.nama, '-'),0) AS nama, IFNULL(SUM(IF( MONTH(tanggal) = $bulan AND YEAR(c.tanggal)='$tahun', c.total_harga, 0)),0) AS total
        FROM detail_pengadaan b 
        JOIN produk a ON a.id_produk=b.id_produk 
        JOIN pengadaan c ON b.id_pengadaan=c.id_pengadaan 
        WHERE c.status='Selesai' GROUP BY a.nama";
        $result = $this->db->query($query);
        return $result->result();     
    }

    
}
?>