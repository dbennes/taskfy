<nav class="navbar align-items-start sidebar sidebar-dark accordion bg-gradient-primary p-0 navbar-dark" style="background: rgb(78,115,223);">
    <div class="container-fluid d-flex flex-column p-0">
        <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="hub.php">
            <div class="sidebar-brand-icon rotate-n-15"></div>
            <div class="sidebar-brand-text mx-3"><span style="color: rgb(255,255,255);">TASKFY</span></div>
        </a>
        <hr class="sidebar-divider my-0">
        <ul class="navbar-nav text-light" id="accordionSidebar">
            <li class="nav-item">
                <a class="nav-link active" href="<?php echo $hub; ?>" style="color: rgb(108,108,108);">
                    <i style="color: #ffffff;" class="fas fa-globe-americas fa-2x text-gray-300"></i>
                    <span style="color: #ffffff;">&nbsp;Hub</span>
                </a>
                <a class="nav-link" href="<?php echo $ftos; ?>" style="color: #ffffff;">
                    <i style="color: #ffffff;" class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    <span style="color: #ffffff;">&nbsp; FTOs</span>
                </a>
                <a class="nav-link" href="<?php echo $lista_interferencia; ?>" style="color: #ffffff;">
                    <i style="color: #ffffff;"  class="fas fa-th-list fa-2x text-gray-300"></i>
                    <span style="color: #ffffff;">&nbsp;Impedimentos</span>
                </a>
                <a class="nav-link" href="<?php echo $avancar; ?>" style="color: #ffffff;">
                    <i style="color: #ffffff;" class="fas fa-check-square fa-2x text-gray-300"></i>
                    <span style="color: #ffffff;">&nbsp;Reg. Avanço</span>
                </a>
                
                <a class="nav-link" href="<?php echo $interferencia; ?>" style="color: #ffffff;">
                    <i style="color: #ffffff;" class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    <span style="color: #ffffff;">&nbsp;Reg. Impedimentos</span>
                </a>
                <a class="nav-link" target="_blank" href="https://app.powerbi.com/links/e_d5rUKLzc?ctid=f68d0532-006e-4920-9d91-41a63b78c1b2&pbi_source=linkShare" style="color: #ffffff;">
                    <i class="fas fa-chart-pie fa-2x text-gray-300" style="color: #ffffff;"></i>
                    <span style="color: #ffffff;">&nbsp;Dashboard</span>
                </a>
            </li>

            <!--
            <li class="nav-item" style="color: #ffffff;">
                <a class="nav-link" href="<?php echo $hub; ?>" style="color: #ffffff;">
                    <i class="far fa-copy" style="color: #ffffff;"></i>
                    <span style="color: #ffffff;">SMAs</span>
                </a>
                <a class="nav-link" href="<?php echo $hub; ?>" style="color: #ffffff;">
                    <i class="far fa-file-alt" style="color: #ffffff;"></i>
                    <span style="color: #ffffff;">&nbsp;Relatórios</span>
                </a>
                <a class="nav-link" href="<?php echo $importar_fto; ?>" style="color: #ffffff;">
                    <i class="fas fa-external-link-alt" style="color: #ffffff;"></i>
                    <span style="color: #ffffff;">&nbsp;Importar FTOs</span>
                </a>
                
            </li>
            <li class="nav-item" style="color: #ffffff;">
               
            </li>-->
        </ul>
        <div class="text-center d-none d-md-inline">
            <button class="btn rounded-circle border-0" id="sidebarToggle" type="button"></button>
        </div>
    </div>
</nav>
<div class="d-flex flex-column" id="content-wrapper">
    <div id="content">
        <nav class="navbar navbar-expand bg-white shadow mb-4 topbar static-top navbar-light">
            <div class="container-fluid">
                <button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button">
                    <i class="fas fa-bars"></i>
                </button>
                <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input class="bg-light form-control border-0 small" type="text" placeholder="Pesquisar ...">
                        <button class="btn btn-primary py-0" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <ul class="navbar-nav flex-nowrap ms-auto">
                    <li class="nav-item dropdown d-sm-none no-arrow">
                        <a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#">
                            <i class="fas fa-search"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-3 animated--grow-in" aria-labelledby="searchDropdown">
                            <form class="me-auto navbar-search w-100">
                                <div class="input-group">
                                    <input class="bg-light form-control border-0 small" type="text" placeholder="Search for ...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary py-0" type="button">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li>
                    <div class="d-none d-sm-block topbar-divider"></div>
                    <li class="nav-item dropdown no-arrow">
                        <div class="nav-item dropdown no-arrow">
                            <a class="dropdown-toggle nav-link" aria-expanded="false" data-bs-toggle="dropdown" href="#">
                                <span class="d-none d-lg-inline me-2 text-gray-600 small"> <?php echo  $email; ?></span>
                            </a>
                            <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in">
                                
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../php/funcoes/login/logout.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
