@extends('layouts.app')

@section('title', 'Urlaub anlegen')

@section('content')
    @component('components.container')
        <div class="card">
            <div class="card-header">
                Urlaub anlegen
            </div>
            <div class="card-body">
                @component('components.errors')
                @endcomponent
                <form method="post" action="{{ route('absences.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="user_id">Anlegen für:</label>
                        <select class="form-control fancy-selectize" name="user_id">
                            @foreach($users as $thisUser)
                                <option value="{{ $thisUser->id }}" @if($user->id == $thisUser->id) selected @endif>{{ $thisUser->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="reason">Zeitraum:</label>
                        <input type="hidden" name="month" value="{{ $month }}" />
                        <input type="hidden" name="year" value="{{ $year }}" />
                        <input type="hidden" id="from" name="from" />
                        <input type="hidden" id="to" name="to" />
                        <div id="date-range12"></div>
                        <div id="date-range12-container"></div>
                    </div>
                    <div class="form-group">
                        <label for="reason">Beschreibung:</label>
                        <input type="text" class="form-control" name="reason" value="Urlaub" placeholder="z.B. Urlaub, Fortbildung, o.ä."/>
                    </div>
                    <h3>Vertretungen</h3>
                    <div id="frmReplacements">
                        <div class="hidden" style="display: none">
                            <div class="container1">
                                <select class="form-control replacement-user" multiple>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Vertreter*in</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Zeitraum</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button id="btnAddReplacement" class="btn btn-secondary">Vertretung hinzufügen</button>
                    <hr />
                    <button type="submit" class="btn btn-primary">Speichern</button>
                </form>
            </div>
        </div>
        <script>
            var replacementCtr = 0;

            function initDeleteButtons() {
                $('.btnDeleteReplacement').click(function(e){
                    e.preventDefault;
                    $(this).parent().parent().remove();
                });
            }

            $(function() {

                initDeleteButtons();

                $('.peopleSelect').selectize({
                    create: true,
                    render: {
                        option_create: function (data, escape) {
                            return '<div class="create">Neue Person anlegen: <strong>' + escape(data.input) + '</strong>&hellip;</div>';
                        }
                    },
                });


                $('#date-range12').dateRangePicker({
                    separator : ' - ',
                    format: 'DD.MM.Y',
                    inline:true,
                    container: '#date-range12-container',
                    alwaysOpen:true,
                    language: 'de',
                    stickyMonths: true,
                    selectForward: true,
                    monthSelect: true,
                    yearSelect: true,
                    defaultTime: '{{ $year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}-01T00:00:00',
                    getValue: function()
                    {
                        if ($('#from').val() && $('#to').val() )
                            return $('#from').val() + ' - ' + $('#to').val();
                        else
                            return '';
                    },
                    setValue: function(s,s1,s2)
                    {
                        $('#from').val(s1);
                        $('#to').val(s2);
                    }
                });

                $('.replacement-row').each(function(){
                    var rowIdx = $(this).data('row');
                    var rowId = '#'+$(this).attr('id');
                    $(rowId+'-from').dateRangePicker({
                        separator : ' - ',
                        format: 'DD.MM.Y',
                        language: 'de',
                        stickyMonths: true,
                        selectForward: true,
                        monthSelect: true,
                        yearSelect: true,
                        autoClose: true,
                        defaultTime: '{{ $year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}-01T00:00:00',
                        getValue: function()
                        {
                            if ($(rowId+' .input-from').val() && $(rowId+' .input-to').val() )
                                return $(rowId+' .input-from').val() + ' - ' + $(rowId+' .input-to').val();
                            else
                                return '';
                        },
                        setValue: function(s,s1,s2)
                        {
                            $(rowId+' .input-from').val(s1);
                            $(rowId+' .input-to').val(s2);
                        }
                    });
                });


                $('#btnAddReplacement').click(function(e){
                    e.preventDefault();
                    replacementCtr++;

                    $('#frmReplacements').append(
                        '<div class="row" id="replacement-row-'+replacementCtr+'">'
                        +'<div class="col-4"><div class="form-group">'
                        +$('#frmReplacements .hidden .container1').html()
                        +'</div></div>'
                        +'<div class="col-3"><div class="form-group" id="replacement-row-'+replacementCtr+'-from">'
                        +'<input type="text" class="form-control input-from" name="replacement['+replacementCtr+'][from]" placeholder="TT.MM.JJJJ" value="'+$('#from').val()+'"/>'
                        +'</div></div>'
                        +'<div class="col-3"><div class="form-group">'
                        +'<input type="text" class="form-control input-to" name="replacement['+replacementCtr+'][to]" placeholder="TT.MM.JJJJ" value="'+$('#to').val()+'" />'
                        +'</div></div>'
                        +'<div class="col-2 pull-right"><button class="btn btn-sm btn-danger btnDeleteReplacement" title="Vertretung entfernen"><span class="fa fa-trash"></span></button></div>'
                        +'</div>'
                    );

                    initDeleteButtons();

                    $('#replacement-row-'+replacementCtr+' .replacement-user')
                        .attr('name', 'replacement['+replacementCtr+'][user][]');
                    $('#replacement-row-'+replacementCtr+' .replacement-user').selectize({
                        create: true,
                        render: {
                            option_create: function (data, escape) {
                                return '<div class="create">Neue Person anlegen: <strong>' + escape(data.input) + '</strong>&hellip;</div>';
                            }
                        },
                    });


                    $('#replacement-row-'+replacementCtr+'-from').dateRangePicker({
                        separator : ' - ',
                        format: 'DD.MM.Y',
                        language: 'de',
                        stickyMonths: true,
                        selectForward: true,
                        monthSelect: true,
                        yearSelect: true,
                        autoClose: true,
                        defaultTime: '{{ $year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}-01T00:00:00',
                        getValue: function()
                        {
                            if ($('#replacement-row-'+replacementCtr+' .input-from').val() && $('#replacement-row-'+replacementCtr+' .input-to').val() )
                                return $('#replacement-row-'+replacementCtr+' .input-from').val() + ' - ' + $('#replacement-row-'+replacementCtr+' .input-to').val();
                            else
                                return '';
                        },
                        setValue: function(s,s1,s2)
                        {
                            $('#replacement-row-'+replacementCtr+' .input-from').val(s1);
                            $('#replacement-row-'+replacementCtr+' .input-to').val(s2);
                        }
                    });

                    $('#replacement-row-'+replacementCtr+' .selectize-input input').first().focus();
                });
            });
        </script>
    @endcomponent
@endsection