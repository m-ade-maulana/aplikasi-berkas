<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sistem Aplikasi Berkas</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

	<!-- datatable -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

	<!-- FontAwesome -->
	<script src="https://kit.fontawesome.com/3fee3556d5.js" crossorigin="anonymous"></script>
</head>

<body>

	<nav class="navbar navbar-expand-lg bg-primary">
		<div class="container">
			<a class="navbar-brand text-white" href="#">Sistem Aplikasi Berkas</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<div class="me-auto mb-2 mb-lg-0"></div>
				<div class="d-flex" role="search">
					<ul class="navbar-nav">

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								Selamat Datang, <?= $this->session->userdata('nama') ?>
							</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="<?= base_url('welcome/logout') ?>">Logout</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</nav>
	<div class="container">
		<div class="card mt-5 mx-auto border-0 shadow">
			<div class="card-body">
				<div class="card shadow border-0 rounded">
					<div class="card-body">
						<?= form_open_multipart('dashboard/do_upload/' . rand(11111, 99999)) ?>
						<div class="row">
							<div class="col-2">
								<label for="" class="col-form-label fw-bold">Masukan Berkas</label>
							</div>
							<div class="col-8">
								<input type="file" class="form-control" name="userfile">
							</div>
							<div class="col-2">
								<div class="d-gap d-grid">
									<button type="submit" class="btn btn-primary fw-bold"><i class="fa-solid fa-upload"></i> Upload Berkas</button>
								</div>
							</div>
						</div>
						</form>
					</div>
				</div>
				<div class="card mt-3 shadow border-0 rounded">
					<div class="card-body">
						<table id="example" class="table table-striped border" style="width:100%">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama Berkas</th>
									<th>Tanggal Upload</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
								<!-- Data Disini -->
								<?php
								$no = 1;
								foreach ($data_upload as $du) { ?>
									<tr>
										<td><?= $no++ ?></td>
										<td><?= $du['nama_file'] ?></td>
										<td><?= $du['tanggal_upload'] ?></td>
										<td>
											<div class="row">
												<div class="col-sm-12">
													<div class="d-grid d-gap">
														<a href="<?= base_url('./upload/') ?>" class="btn btn-primary"><i class="fa-solid fa-download"></i> Download</a>
													</div>
												</div>
											</div>
										</td>
									</tr>
								<?php }
								?>
							</tbody>
						</table>
					</div>
				</div>

			</div>
		</div>
		<div class="mt-5">
			<p class="text-center">Created By <a href="https://m-ade-maulana.github.io/" class="text-decoration-none" target="_blank">M Ade Maulana</a></p>
		</div>
	</div>

	<script>
		<?= $this->session->flashdata('message') ?>
	</script>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

	<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

	<script>
		$(document).ready(function() {
			$('#example').DataTable();
		});
	</script>
</body>

</html>