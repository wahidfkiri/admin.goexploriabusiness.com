 <link rel="stylesheet" href="{{asset('front/css/main.css')}}">
 
            <header id="header" data-transparent="true" data-fullwidth="true" class="dark submenu-light">
            <div class="header-inner">
                <div class="container">
                    <!--Logo-->
                    <div id="logo">
                        <a href="index.html">
                            <img src="{{asset('logo.png')}}" class="d-block">
                        </a>
                    </div>
                    <!--End: Logo-->
                    <!-- Search -->
                    <div id="search"><a id="btn-search-close" class="btn-search-close" aria-label="Close search form"><i class="icon-x"></i></a>
                        <form class="search-form" action="search-results-page.html" method="get">
                            <input class="form-control" name="q" type="text" placeholder="Type & Search...">
                            <span class="text-muted">Start typing & press "Enter" or "ESC" to close</span>
                        </form>
                    </div>
                    <!-- end: search -->
                    <!--Header Extras-->
                    <div class="header-extras">
                        <ul>
                            <li>
                                <a id="btn-search" href="#"> <i class="icon-search"></i></a>
                            </li>
                            <li>
                                <div class="p-dropdown">
                                    <a href="#"><i class="icon-globe"></i><span>EN</span></a>
                                    <ul class="p-dropdown-content">
                                        <li><a href="#">French</a></li>
                                        <li><a href="#">Spanish</a></li>
                                        <li><a href="#">English</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!--end: Header Extras-->
                    <!--Navigation Resposnive Trigger-->
                    <div id="mainMenu-trigger">
                        <a class="lines-button x"><span class="lines"></span></a>
                    </div>
                    <!--end: Navigation Resposnive Trigger-->
                    <div id="mainMenu">
                        <div class="container">
                            <nav>
                                <ul>
                                    <li class="dropdown mega-menu-item"><a href="#">Destinations</a>
                                        <ul class="dropdown-menu">
                                            <li class="mega-menu-content">
                                                <div class="row">
                                                    @foreach(\App\Models\Continent::active()->get() as $key)
                                                    <div class="col-lg-3">
                                                         <div class="card">
                                                          <div class="card-body d-flex align-items-center gap-3">
                                                           <a href="{{url('continent')}}/{{$key->code}}" class="align-items-center text-decoration-none d-grid" style="text-align:center">
                                                             <img src="{{asset('storage/continents')}}/{{$key->image}}"  class="align-items-center w-100 me-3" style="height: 275px;">
                                                               <span class="fw-semibold">{{$key->name}}</span>
                                                               </a>
                                                           </div>
                                                           </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    @foreach(\App\Models\CategorieType::all() as $item)
                                    <li class="dropdown mega-menu-item"><a href="#">{{$item->name}}</a>
                                        <ul class="dropdown-menu">
                                            <li class="mega-menu-content">
                                                <div class="row">
                                                    @foreach(\App\Models\Category::where('categorie_type_id', $item->id)->get() as $categorie)
                                                    <div class="col-lg-2-5">
                                                        <ul>
                                                            <li class="mega-menu-title">{{$categorie->name}}</li>
                                                            @foreach(\App\Models\Activity::where('categorie_id', $categorie->id)->get() as $activity)
                                                            <li><a href="blog-masonry-2.html">{{$activity->name}}</a></li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                    @endforeach
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <!--end: Navigation-->
                </div>
            </div>
        </header>

         <script src="{{asset('front/js/jquery.js')}}"></script>
    <!-- <script src="{{asset('front/js/plugins.js')}}"></script> -->
    <!--Template functions-->
    <script src="{{asset('front/js/functions.js')}}"></script>
