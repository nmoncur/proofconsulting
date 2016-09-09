<?php
/*
 Template Name: Home
*/

get_header();
?>

<div class="row">
  <div class="col-sm-10 col-sm-offset-1 text-center">

    <h1>Welcome to Proof</h1>

    <div class="col-xs-6">
      <h2 class="col-xs-offset-1">Client Login</h2>
      <h3 class="col-xs-offset-1">
        <div class="coming-soon">Coming Soon!</div>
      </h3>
    </div>

    <div class="col-xs-6">
      <h2 class="col-xs-offset-1">Visitor Section</h2>
      <h3 class="col-xs-offset-1">
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#visitorModal">Login</button>
      </h3>

      <div id="visitorModal" class="modal fade visitorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Visitor Login</h4>
            </div>
            <div class="modal-body">
              <div>
                <h5>Please enter your email address to enter the visitor section:</h5>
                <div class="row">
                  <div class="col-xs-8 col-xs-offset-2">
                    <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" class="form">
                      <div class="form-group">
                        <label class="sr-only">Name</label>
                        <input id="visitor-name" name="name" class="form-control" placeholder="Name">
                      </div>
                      <div class="form-group">
                        <label class="sr-only">Email address</label>
                        <input id="visitor-email" name="email" class="form-control" placeholder="Email">
                      </div>
                      <div>
                        <div id="feedback"></div>
                      </div>
                      <input type="hidden" name="action" value="proof_email_list_post">
                      <input id="submit-visitor-info" type="submit" value="Enter" style="display:none;">
                    </form>
                    <div class="modal-footer">
                      <button class="btn btn-default" onclick="visitorLogin()">Enter</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php get_footer(); ?>
