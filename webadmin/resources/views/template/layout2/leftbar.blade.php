<div class="nk-sidebar-body">
    <div class="nk-sidebar-content" data-simplebar>
        <div class="nk-sidebar-menu">
            <!-- Menu -->
            @php
                $module = Session::get('Tsmodule');
            @endphp
            <ul class="nk-menu apps-menu">
                <?php echo $module ?>
                <li class="nk-menu-hr"></li>
            </ul>
        </div>
        {{-- <div class="nk-sidebar-footer">
            <ul class="nk-menu nk-menu-md">
                <li class="nk-menu-item">
                    <a href="#" class="nk-menu-link" title="Settings">
                        <span class="nk-menu-icon"><em class="icon ni ni-setting"></em></span>
                    </a>
                </li>
            </ul>
        </div> --}}
    </div>
</div>
<script type="text/javascript">
    function gotodash(groupdash)
    {
        window.location.href = "{{url('/administrator/gotodash')}}"+"/"+btoa(groupdash);
    }
</script>
