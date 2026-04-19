<?php
use KrisRo\PhpRepublic\ApiTokens;
use KrisRo\PhpRepublic\Request as R;
?>
      <div class="col-lg-3 col-sm-12">
        <div class="card card-primary card-outline">
          <form action="/admin/account/user/token/add" method="POST">

            <?php echo self::getFormToken('user-token-add') // self is instance of KrisRo\PhpRepublic\Template ?>

            <div class="card-body box-profile">

              <div class="form-group">
                <label>Label</label>
                <input name="label" value="<?php echo R::post('label') ?: R::get('label', '') ?>" type="text" class="form-control" aria-describedby="labelHelp">
                <?php if (!(self::view('errors/label') ?? null)) { ?>
                  <div id="labelHelp" class="form-text">
                    A name to remember :).
                  </div>
                <?php } else { ?>
                  <div class="invalid-feedback">
                    <?php echo self::view('errors/label') ?? '' ?>
                  </div>
                <?php } ?>
              </div>

              <div class="form-group mt-2">
                <label>Expire in</label>
                <select name="expire" class="form-control">
                  <option value=""> -- </option>
                  <?php foreach (ApiTokens::expirationIntervals() as $date => $label) { ?>
                    <option value="<?php echo $date ?>" <?php echo $date == R::post('expire') ? 'selected' : '' ?>><?php echo $label ?></option>
                  <?php } ?>
                </select>
                <?php if (self::view('errors/expire') ?? null) { ?>
                  <div class="invalid-feedback">
                    <?php echo self::view('errors/expire') ?? '' ?>
                  </div>
                <?php } ?>
              </div>

              <div class="form-group mt-2">
                <label>Your password</label>
                <input name="password" value="<?php echo R::post('password', '') ?>" type="password" class="form-control" aria-describedby="passwordHelp">
                <?php if (!(self::view('errors/password') ?? null)) { ?>
                  <div id="passwordHelp" class="form-text">
                    Just to be sure.
                  </div>
                <?php } else { ?>
                  <div class="invalid-feedback">
                    <?php echo self::view('errors/password') ?? '' ?>
                  </div>
                <?php } ?>
              </div>

              <?php if (!R::isPost() || !empty(self::view('errors'))) { ?>
                <div class="form-group mt-4 text-end">
                  <button type="submit" class="btn btn-primary"><i class="nav-icon bi bi-key-fill me-2"></i> Generate Token</button>
                </div>
              <?php } else {  ?>
                <div class="form-group mt-4 position-relative">
                  <label>Copy token</label>
                  <button class="btn btn-link btn-sm text-decoration-none text-dark position-absolute"
                          style="top: -1px; right: 5px;"
                          id="copy-btn"
                          data-default-text="Copy"
                          data-default-copied="Copied!"
                          ><i class="bi bi-copy me-1"></i> Copy</button>
                  <textarea class="form-control" rows="3" id="token-copy" disabled aria-describedby="tokenHelp"><?php echo self::user_token() ?></textarea>
                  <div id="tokenHelp" class="form-text">
                    This is the only time you can see this. Copy it to a safe place.
                  </div>
                </div>
              <?php }  ?>
            </div>
            <!-- /.card-body -->
          </form>
        </div>
      </div>