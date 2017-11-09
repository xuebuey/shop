{extend name="common/layout" /}

{block name="content"}
<div id="content">

    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-set" data-toggle="tooltip" title="保存" class="btn btn-primary">
                    <i class="fa fa-save"></i>
                </button>
                <a href="{:url('index')}" data-toggle="tooltip" title="取消" class="btn btn-default">
                    <i class="fa fa-reply"></i>
                </a>
            </div>
            <h1>%table_title%{if condition="$id"}编辑{else/}创建{/if}</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="{:url('site/index')}">首页</a>
                </li>
                <li>
                    <a href="{:url('index')}">%table_title%</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-pencil"></i>
                    %table_title%{if condition="$id"}编辑{else/}创建{/if}
                </h3>
            </div>
            <div class="panel-body">
                <form action="{:url('set', ['id'=>$id])}" method="post" enctype="multipart/form-data" id="form-set" class="form-horizontal">
                    {:token()}

                    {if condition="$id"}
                    <input type="hidden" name="id" value="{$id}">
                    {/if}
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab-general" data-toggle="tab">基本信息</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">
%form_field_list%
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{/block}

{block name="title"}%table_title%{if condition="$id"}编辑{else/}创建{/if}{/block}

{block name="appendCss"}
{/block}

{block name="appendJs"}
<script src="__STATIC__/validate/jquery.validate.min.js"></script>
<script src="__STATIC__/validate/additional-methods.min.js"></script>
<script src="__STATIC__/validate/localization/messages_zh.min.js"></script>
<script>
    $(function() {
        $('#form-set').validate({
            // 定义规则
            rules: {

            },
            // 定义错误消息
            messages: {

            },

            errorClass: 'text-danger',
        });
    });
</script>
{/block}