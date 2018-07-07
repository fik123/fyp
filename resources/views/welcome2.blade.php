<!DOCTYPE html>

<html>
  <head>
    <title>The Modernist</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="layout/styles/layout.css" type="text/css">
  </head>
  <body>
      <body id="top">  
      <div class="wrapper row1"> 
  
  <header id="header" class="clear"> 
    
    <div id="logo">
      <h1>Kiki's Kitchen</a></h1>
    </div>

    <nav>
      <ul class="clear">
        
        @auth
            <li class="active"><a href="{{ url('/home') }}">Home</a></li>
        @else
            <li class="active"><a href="{{ route('login') }}">Login</a></li>
            <li class="active"><a href="{{ route('register') }}">Register</a></li>
        @endauth
      </ul>
    </nav>
     
  </header>

  <div id="slider" class="jcarousel-wrapper">
    <div id="slider_content" class="jcarousel">
      <ul>
        <li>
          <figure><a href="#"><img src="images/slider/1.png" alt=""></a>
            <figcaption>
              <h2>Cursus penati saccum nulla.</h2>
              <p>Nullamlacus dui ipsum conseque loborttis non euisque morbi penas dapibulum orna. Urnaultrices quis curabitur phasellentesque congue magnis vestibulum quismodo nulla et feugiat adipiscinia pellentum leo&hellip;</p>
              <footer><a href="#">Read More Here &raquo;</a></footer>
            </figcaption>
          </figure>
        </li>
        <li>
          <figure><a href="#"><img src="images/slider/2.png" alt=""></a>
            <figcaption>
              <h2>Cursus penati saccum nulla.</h2>
              <p>Nullamlacus dui ipsum conseque loborttis non euisque morbi penas dapibulum orna. Urnaultrices quis curabitur phasellentesque congue magnis vestibulum quismodo nulla et feugiat adipiscinia pellentum leo&hellip;</p>
              <footer><a href="#">Read More Here &raquo;</a></footer>
            </figcaption>
          </figure>
        </li>
        <li>
          <figure><a href="#"><img src="images/slider/3.png" alt=""></a>
            <figcaption>
              <h2>Cursus penati saccum nulla.</h2>
              <p>Nullamlacus dui ipsum conseque loborttis non euisque morbi penas dapibulum orna. Urnaultrices quis curabitur phasellentesque congue magnis vestibulum quismodo nulla et feugiat adipiscinia pellentum leo&hellip;</p>
              <footer><a href="#">Read More Here &raquo;</a></footer>
            </figcaption>
          </figure>
        </li>
      </ul>
    </div>
    <!-- Previous Next --> 
    <a href="#" id="featured-item-prev" class="jcarousel-control-prev">&laquo; Previous</a> <a href="#" id="featured-item-next" class="jcarousel-control-next">Next &raquo;</a> 
    <!-- / Previous Next --> 
  </div>
  <!-- ########################################################################################## --> 
      </div>
     
      <div class="wrapper row2">
        <main id="container" class="clear"> 
    <!-- container body --> 
    <!-- ########################################################################################## -->
    <div id="homepage"> 
      <!-- Services -->
      <div id="services">
        <ul class="clear">
          <li>
            <article> <a class="service s-1" href="#">
              <h2>Admin</h2>
              <p>Vestassapede et donec ut est libe ros sus et eget sed eget quisq ueta habitur augue</p>
              </a> </article>
          </li>
          <li>
            <article> <a class="service s-2" href="#">
              <h2>Kitchen</h2>
              <p>Vestassapede et donec ut est libe ros sus et eget sed eget quisq ueta habitur augue</p>
              </a> </article>
          </li>
          <li>
            <article> <a class="service s-3" href="#">
              <h2>Waiter</h2>
              <p>Vestassapede et donec ut est libe ros sus et eget sed eget quisq ueta habitur augue</p>
              </a> </article>
          </li>
          <li>
            <article> <a class="service s-4" href="#">
              <h2>Cashier</h2>
              <p>Vestassapede et donec ut est libe ros sus et eget sed eget quisq ueta habitur augue</p>
              </a> </article>
          </li>
        </ul>
      </div>
      <!-- / Services --> 
    </div>
    <!-- ########################################################################################## --> 
    <!-- / container body -->
    <div class="clear"></div>
        </main>
      </div>
      
     
      <!-- JAVASCRIPTS --> 
      <script src="layout/scripts/jquery.min.js"></script> 
      <script src="layout/scripts/jquery.grayscale.js"></script> 
      <script src="layout/scripts/carousel/jquery.jcarousel.pack.js"></script> 
      <script src="layout/scripts/carousel/jquery.jcarousel.setup.js"></script>
    </body>
</html>