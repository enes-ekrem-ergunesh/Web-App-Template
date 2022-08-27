class CommonFrontend{
  static _dir;

  static init(_dir){
    CommonService._dir = _dir;
  }

  static listNavbar(){

    var themevar1 = [``, `bg-dark`];
    var themevar2 = [`style="background-color: #e3f2fd;"`, ``];
    var themevar3 = [`primary`, `light`];
    var themevar4 = [`primary`, `warning`];
    
    var themeMod = 0;
    if(CommonService.isDark()) themeMod = 1;

    $('#body-header').html(`
      <nav class="navbar navbar-expand-md `+themevar1[themeMod]+`"`+themevar2[themeMod]+`>
        <div class="container-fluid">
          <a class="navbar-brand" href="#">Navbar</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01"
            aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav me-auto mb-2 mb-md-0" style="/*width: -webkit-fill-available; width: -moz-available;*/">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Features</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Pricing</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">About</a>
              </li>
            </ul>
            <form class="d-flex" role="search">
              <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
            </form>
            <div class="d-flex text-end">
              <button type="button" class="btn btn-outline-`+themevar3[themeMod]+` me-2"  data-bs-toggle="modal" data-bs-target="#login-modal">Login</button>
              <button type="button" class="btn btn-`+themevar4[themeMod]+`">Sign-up</button>
            </div>
          </div>
        </div>
      </nav>
    `);
  }


}