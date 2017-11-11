<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> {!! DFBUILDER_VERSION !!}
    </div>
    <strong>Copyright &copy; {!! date('Y') !!} <a href="http://www.dfbuilder.com">Dfbuilder.com</a>.</strong> All rights
    reserved.
</footer>




<script src="/js/app.js?time={!! time() !!}"></script>
<script src="/js/dfbuilder.js?time={!! time() !!}"></script>
@if(Session::has('flash_info_noty'))
    <script>
        (function($){
            $( document ).ready(function() {
                $.fn.notyMsg('{!! Session::get('flash_info_noty') !!}',notyMessageTypes.information,3500,notyPositons.top);
            });
        })(jQuery);
    </script>
    @endif


@if(Session::has('flash_success_noty'))
    <script>
        (function($){
            $.fn.notyMsg('{!! Session::get('flash_success_noty') !!}',notyMessageTypes.success,3500,notyPositons.top);
        })(jQuery);
    </script>
@endif

</body>
</html>