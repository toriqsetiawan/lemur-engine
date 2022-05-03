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


            <!-- loop through all the sections in their 'order' and then populate with the items which exist for them -->

            @foreach($allSections as $sectionId => $sectionGroup)


            @if(!empty($botProperties[$sectionGroup->slug]))

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



                        @foreach($botProperties[$sectionSlug] as $name => $value)



                                <div class='form-group col-md-4 col-sm-6 col-xs-12' data-test='{!! $name !!}_div'>
                                    <label for='{!! $name !!}_field' data-test='{!! $name !!}_label'>{!! $name !!}:</label>
                                    <div class='input-group'>
                                    <input type='text' name='name[{!! $name !!}]' value='{!! $value !!}' class='form-control' id='{!! $name !!}_value_field' data-test='{!! $name !!}_value_field'>
                                        <div class="input-group-btn">
                                            <button name="edit" class="btn btn-sm btn-info">Edit</button>
                                            <button name="delete" class="btn btn-sm btn-danger">Delete</button>
                                        </div>

                                    </div>
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
