<x-mail::message>
#   don't forget your {{$event_name}} event at {{$event_date}}.

    your event will include :
      - {{$event_description}}


    we are waiting for you.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
