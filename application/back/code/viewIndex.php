{extend name="common/layout" /}

{block name="content"}
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="{:url('set')}" data-toggle="tooltip" title="新增" class="btn btn-primary"> <i class="fa fa-plus"></i>
                </a>
                <button type="button" data-toggle="tooltip" title="删除" class="btn btn-danger" onclick="confirm('确认？') ? $('#form-index').submit() : false;">
                    <i class="fa fa-trash-o"></i>
                </button>
            </div>
            <h1>%table_title%管理</h1>
            <ul class="breadcrumb">
                <li>
                    <a href="{:url('site/index')}">首页</a>
                </li>
                <li>
                    <a href="{:url('index')}">%table_title%管理</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-list"></i>
                    %table_title%列表
                </h3>
            </div>
            <div class="panel-body">
                <form action="{:url('index')}" method="get">
                    <div class="well">
                        <div class="row">

%search_field_list%
                        </div>

                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <button type="submit" id="button-filter" class="btn btn-primary pull-right">
                                    <i class="fa fa-search"></i>
                                    筛选
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <form action="{:url('multi')}" method="post" id="form-index">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <td style="width: 1px;" class="text-center">
                                    <input type="checkbox" onclick="$('input[name=\'selected[]\']').prop('checked', this.checked);" />
                                </td>
%table_head_list%
                                <td class="text-right">管理</td>
                            </tr>
                            </thead>
                            <tbody>
                            {if condition="$paginator->total() > 0"}
                            {foreach $paginator as $row}
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="selected[]" value="{$row['id']}" />
                                </td>
%table_data_list%
                                <td class="text-right">
                                    <a href="{:url('set', ['id'=>$row['id']])}" data-toggle="tooltip" title="编辑" class="btn btn-primary">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                            {/foreach}
                            {else/}
                            <tr>
                                <td class="text-center" colspan="%column_number%">
                                    无记录
                                </td>
                            </tr>
                            {/if}

                            </tbody>
                        </table>
                    </div>
                </form>
                <div class="row">
                    <div class="col-sm-6 text-left">
                        {$paginator->render()}
                    </div>
                    <div class="col-sm-6 text-right">显示开始 {$start} 到 {$end} 之 {$paginator->total()} （总 {$paginator->lastPage()} 页）</div>
                </div>
            </div>
        </div>
    </div>
</div>
{/block}