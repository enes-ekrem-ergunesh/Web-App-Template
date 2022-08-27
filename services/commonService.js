class CommonService{
  static _dir;

  static init(_dir){
    CommonService._dir = _dir;
  }

  static getToken(){
    return localStorage.getItem("token");
  }

  static goToPage(page){
    window.location.href = _dir+page;
  }

  static toggleLoginModal(){
    if(CommonService.getToken() == null){
      // toggle login modal
    }
  }

  static isDark(){
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
  }


}