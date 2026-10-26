  <div class="row-fluid">
              
              <div class="span12">
               
                  <div class="widget-body">
                    <div class="span3">&nbsp;</div>
                    <div class="span6">
                      <div class="sign-in-container">
                        <?php echo form_open('','class="login-wrapper"') ?> <?php echo form_hidden('cmd', 'login') ?>
                          <div class="header">
                            
                              <div class="span12">
                                <h3>PMTS LOGIN <img src="img/konten_01.png" alt="Logo" class="pull-left"></h3>
                                <p>Fill out the form below to login.</p>
                              </div>
							
                           
                          </div>
                          <div class="content">
						  	<?php if ($errors = validation_errors()) { ?>
							<div class="row-fluid">
                              <div class="span12">
							    <div class="alert alert-block alert-error fade in">
								  <button data-dismiss="alert" class="close" type="button">
									x
								  </button>
								  <h4 class="alert-heading">
									Error!
								  </h4>
								  <p>
									<?php echo $errors; ?>
								  </p>
								 
								</div>
							 </div>
                            </div>
							<?php } ?>
                            <div class="row-fluid">
                              <div class="span12">
							  <?php echo @form_input('username', set_value('username', ''), 'autocomplete="off" class="input span12 email" autofocus="1" placeholder="Username" required="required"') ?>
                              </div>
                            </div>
                            <div class="row-fluid">
                              <div class="span12">
							   <?php echo @form_password('password', set_value('name', ''), 'class="input span12 password" required="required" placeholder="Password"') ?>
                               
                              </div>
                            </div>
							<div class="row-fluid">
                              <div class="span12">
								<img id="siimage" style="border:1px solid #ddd; margin-bottom:10px;" src="<?php echo base_url('index.php/users/security_code?sid='.md5(uniqid())) ?>" alt="CAPTCHA Image" />
							  </div>
							</div>
							<div class="row-fluid">
                              <div class="span12">
								<?php echo @form_input('code', set_value('code', ''), 'autocomplete="off" id="code" placeholder="Security Code" class="input span12"') ?>
                              </div>
                            </div>
                          </div>
                          <div class="actions">
                            <input class="btn btn-danger" name="Login" type="submit" value="Login" >
                            <a class="link" href="#">   
</a>
                            <div class="clearfix"></div>
                          </div>
                         <?php echo form_close() ?>
                      </div>
                    </div>
                    <div class="span3">&nbsp;</div>
                    <div class="clearfix"></div>
                  </div>
                
              </div>