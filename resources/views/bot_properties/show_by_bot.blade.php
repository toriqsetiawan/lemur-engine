<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">




    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>



        @if(count($botProperties)<=0)

            <div class="alert alert-info">There are no {!! strtolower($title) !!} associated with this bot </div>

        @else
            {!! Form::open(['route' => 'botProperties.store', 'data-test'=>$htmlTag.'-create-form', 'class'=>'validate', 'name'=>$htmlTag.'-create']) !!}
            {!! Form::hidden('bulk', 1) !!}
            {!! Form::hidden('bot_id', $bot->slug,['data-test'=>$htmlTag."-bot_id"] ) !!}
            {!! Form::hidden('redirect_url', url()->current(),['data-test'=>"$htmlTag-redirect-url"] ) !!}



            @foreach($allSections as $sectionId => $sectionGroup)

                @if(!empty($botProperties[$sectionId]))

                @php $sectionName = $sectionGroup['name']; @endphp
                @php $sectionSlug = $sectionGroup['slug']; @endphp
                    @if($sectionGroup['default_state'] == 'open')
                        @php $sectionShow = 'true'; @endphp
                        @php $collapseShow = 'collapse in'; @endphp

                    @else
                        @php $sectionShow = 'false'; @endphp
                        @php $collapseShow = 'collapse'; @endphp
                    @endif

                <!--open the previous collaspe box-->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{!! $sectionName !!} Section</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-toggle="collapse" href="#{!! $sectionSlug !!}" role="button" aria-expanded="{!! $sectionShow !!}" aria-controls="{!! $sectionSlug !!}"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body {!! $collapseShow !!}" id="{!! $sectionSlug !!}" aria-expanded="{!! $sectionShow !!}">



                        @foreach($botProperties[$sectionId] as $index => $item)


                                <div class='form-group col-md-4 col-sm-6 col-xs-12' data-test='{!! $item->name !!}_div'>
                                    <label for='{!! $item->name !!}_field' data-test='{!! $item->name !!}_label'>{!! $item->name !!}:</label>
                                    <input type='text' name='name[{!! $item->name !!}]' value='{!! $item->value !!}' class='form-control' id='{!! $item->name !!}_value_field' data-test='{!! $item->name !!}_value_field'>
                                </div>


                            @endforeach

    </div>
    <!-- /.box-body -->
    </div>
    <!-- /.box -->
                @endif
    @endforeach



<!-- Submit Field -->
<div class="form-group col-sm-12">
{!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
<button type="reset" class="btn btn-default">Reset</button>
</div>

{!! Form::close() !!}

@endif




</div>
</section>

@include('layouts.by_bot_add_modal')
