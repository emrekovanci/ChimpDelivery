<p>
    <a class="btn btn-primary btn-block text-left shadow border border-dark" data-toggle="collapse" href="#collapse_app_store_connect_settings" role="button" aria-expanded="false" aria-controls="collapse_app_store_connect_settings">
        <i class="fa fa-apple" aria-hidden="true"></i>
        <b>AppStore API</b>
    </a>
</p>
<div class="collapse" id="collapse_app_store_connect_settings">
    <div class="form-group">
        <a class="badge badge-success" href="https://appstoreconnect.apple.com/access/api" target="_blank">
            <i class="fa fa-external-link" aria-hidden="true"></i> Get Keys
        </a>
    </div>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text">Private Key</span>
        </div>
        <div class="custom-file">
            <label class="custom-file-label" for="private_key">
                <span class="col-7 d-inline-block text-truncate text-secondary font-weight-bold">
                    {{ ($isNew) ? '' : Str::limit($workspace->appStoreConnectSetting->private_key, 128) }}
                </span>
            </label>
            <input type="file" class="custom-file-input" id="private_key" name="private_key" accept=".p8">
        </div>
    </div>
    <div class="form-group">
        <label for="kid">Key ID</label>
        <input type="text" id="kid" name="kid" class="form-control shadow-sm" value="{{ ($isNew) ? '' : $workspace->appStoreConnectSetting->kid }}">
    </div>
    <div class="form-group">
        <label for="issuer_id">Issuer ID</label>
        <input type="text" id="issuer_id" name="issuer_id" class="form-control shadow-sm" value="{{ ($isNew) ? '' : $workspace->appStoreConnectSetting->issuer_id }}">
    </div>
</div>
