 <?php include("inc_header.php") ?>
 <?php
    $sukses = "";
    $katakunci = (isset($_GET['katakunci'])) ? $_GET['katakunci'] : "";
    if (isset($_GET['op'])) {
        $op = $_GET['op'];
    } else {
        $op = "";
    }
    if ($op == 'delete') {
        $id      = $_GET['id'];
        $sql1    = "delete from halaman where id = '$id'";
        $q1      = mysqli_query($koneksi, $sql1);
        if ($q1) {
            $sukses  = "berhasil hapus data";
        }
    }
    ?>
 <h1>Halaman Admin</h1>
 <p>
     <a href="halaman_input.php">
         <input type="button" class="btn btn-primary" value="Buat Halaman Baru" />
     </a>
 </p>

 <?php
    if ($sukses) {
    ?>
     <div class="alert alert-primary" role="alert">
         <?php echo $sukses ?>
     </div>
 <?php
    }
    ?>
 <from class="row g-3" method="get">
     <div class="col-auto">
         <input type="text" class="from-control" placeholder= "Masukkan Kata Kunci" name="katakunci" value="<?php echo $katakunci ?>" />
     </div>
     <div class="col-auto">
         <input type="submit" name="cari" value="Cari Tulisan" class="btn btn-secondary" />
     </div>
 </from>
 <table class="table-table-striped">
     <thead>
         <tr>
             <th class ="col-1">#</th>
             <th class ="col-1">Judul</th>
             <th  class ="col-0">Kutipan</th>
             <th class ="col-3">Aksi</th>
         </tr>
     </thead>
     <tbody>
         <?php
            $sqltambahan ="";
            $per_halaman = 2;
            if ($katakunci != '') {
                $array_katakunci = explode(" ", $katakunci);
                for ($x=0; $x < count($array_katakunci); $x++) {
                    $sqlcari[] = "(judul like '%".$array_katakunci[$x]."'% OR kutipan like '%".$array_katakunci[$x]."'% OR isi like '%".$array_katakunci[$x]."'%)";
                   }
                $sqltambahan   = " WHERE " .implode(" or ", $sqlcari);
            }

            $sql1   = "SELECT * FROM halaman $sqltambahan";
            $page   = isset($_GET['page'])?(int)$_GET['page']:1;
            $mulai  =($page>1) ? ($page * $per_halaman) - $per_halaman:0;
            $q1     = mysqli_query($koneksi, $sql1);
            $total  = mysqli_num_rows($q1);
            $pages  = ceil($total/$per_halaman);
            $nomor  = $mulai + 1;
            $sql1   = $sql1." ORDER BY id DESC limit $mulai, $per_halaman";

            $q1     = mysqli_query($koneksi, $sql1);
            while ($r1 = mysqli_fetch_array($q1)) {
            ?>
             <tr>
                 <td><?php echo $nomor++ ?></td>
                 <td><?php echo $r1['judul'] ?></td>
                 <td><?php echo $r1['kutipan'] ?></td>
                 <td>
                     <a href="halaman_input.php?id=<?php echo $r1['id']?>"
                     <span class="badge bg-warning text-dark">Edit</span>
                     <a href="halaman.php?op=delete&id=<?php echo $r1['id'] ?>" onclick="return confirm('yakin menghapus data?')">
                         <span class="badge bg-danger">Delete</span>
                 </td>
             </tr>
         <?php
            }
            ?>
     </tbody>
 </table>

<nav aria-label="page navigation example">
    <ul class="pagination">
    <?php
    $cari = isset($_GET['cari'])?$_GET['cari']:"";
    for ($i=1; $i <= $pages; $i++){
        ?>
        <li class="page-item">
            <a class="page-link" href="halaman.php?katakunci=<?php echo $katakunci?>&cari=<?php echo $cari?>&page=<?php echo $i ?>"><?php echo $i?></a>
        </li>
        <?php
        }
        ?>
    </ul>
</nav>
 <?php include("inc_footer.php") ?>