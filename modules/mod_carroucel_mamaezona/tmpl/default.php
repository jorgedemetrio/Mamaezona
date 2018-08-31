<?php
// No direct access
defined('_JEXEC') || die;



?>
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators" style="position: absolute; left: 310px; top: 275px; ">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
      <li data-target="#myCarousel" data-slide-to="3"></li>
      </ol>

      <!-- Wrapper for slides -->
      <div class="carousel-inner">

        <div class="item active">
          <a style="color: #eee;" href="<?php echo $url1; ?>" title="<?php echo $titulo1; ?>"><img src="<?php echo $image1; ?>"
          		alt="<?php echo $titulo1; ?>" title="<?php echo $titulo1; ?>" style="width: 90%; margin: auto;" border="0"/></a>
          <div class="carousel-caption">
            <h3 style="color: #eee;"><a style="color: #eee;" href="<?php echo $url1; ?>" title="<?php echo $titulo1; ?>"><?php echo $titulo1; ?></a></h3>
            <p style="color: #eee;"><a style="color: #eee;" href="<?php echo $url1; ?>" title="<?php echo $titulo1; ?>"><?php echo $descricao1; ?></a></p>
          </div>
        </div>

                <div class="item">
          <a style="color: #eee;" href="<?php echo $url2; ?>" title="<?php echo $titulo2; ?>"><img src="<?php echo $image2; ?>"
          		alt="<?php echo $titulo2; ?>" title="<?php echo $titulo2; ?>" style="width: 90%; margin: auto;" border="0"/></a>
          <div class="carousel-caption">
            <h3 style="color: #eee;"><a style="color: #eee;" href="<?php echo $url2; ?>" title="<?php echo $titulo2; ?>"><?php echo $titulo2; ?></a></h3>
            <p style="color: #eee;"><a style="color: #eee;" href="<?php echo $url2; ?>" title="<?php echo $titulo2; ?>"><?php echo $descricao2; ?></a></p>
          </div>
        </div>

                <div class="item">
          <a style="color: #eee;" href="<?php echo $url3; ?>" title="<?php echo $titulo3; ?>"><img src="<?php echo $image3; ?>"
          		alt="<?php echo $titulo3; ?>" title="<?php echo $titulo3; ?>" style="width: 90%; margin: auto;" border="0"/></a>
          <div class="carousel-caption">
            <h3 style="color: #eee;"><a style="color: #eee;" href="<?php echo $url3; ?>" title="<?php echo $titulo3; ?>"><?php echo $titulo3; ?></a></h3>
            <p style="color: #eee;"><a style="color: #eee;" href="<?php echo $url3; ?>" title="<?php echo $titulo3; ?>"><?php echo $descricao3; ?></a></p>
          </div>
        </div>

                <div class="item">
          <a style="color: #eee;" href="<?php echo $url4; ?>" title="<?php echo $titulo4; ?>"><img src="<?php echo $image4; ?>"
          		alt="<?php echo $titulo4; ?>" title="<?php echo $titulo4; ?>" style="width: 90%; margin: auto;" border="0"/></a>
          <div class="carousel-caption">
            <h4 style="color: #eee;"><a style="color: #eee;" href="<?php echo $url4; ?>" title="<?php echo $titulo4; ?>"><?php echo $titulo4; ?></a></h4>
            <p style="color: #eee;"><a style="color: #eee;" href="<?php echo $url4; ?>" title="<?php echo $titulo4; ?>"><?php echo $descricao4; ?></a></p>
          </div>
        </div>

      </div>

      <!-- Left and right controls -->
      <a class="left carousel-control" href="#myCarousel" data-slide="prev">
        &lsaquo;
      </a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next">
        &rsaquo;
      </a>
    </div>
<script>

jQuery(document).ready(function (){
	jQuery('.carousel').carousel({
	  interval: 10000
	});
});
</script>
