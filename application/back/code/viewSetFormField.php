                            <div class="form-group %required_class%">
                                <label class="col-sm-2 control-label" for="input-%field%">%label%</label>
                                <div class="col-sm-10">
                                    <input type="text" name="%field%" value="{$data['%field%']|default=''}" placeholder="%label%" id="input-%field%" class="form-control" />
                                    {if condition="isset($message['%field%'])"}
                                    <label for="input-%field%" class="text-danger">{$message['%field%']}</label>
                                    {/if}
                                </div>
                            </div>
