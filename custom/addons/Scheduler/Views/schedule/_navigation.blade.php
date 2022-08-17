<li class="treeview">
    <a href="#">
        <i class="fa fa-calendar"></i>
        <span>{{ trans('Scheduler::texts.scheduler') }}</span>
        <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li><a href="{{ route('scheduler.index') }}"><i
                        class="fa fa-dashboard"></i> {{ trans('Scheduler::texts.dashboard') }}</a>
        </li>
        <li><a href="{{ route('scheduler.fullcalendar') }}"><i
                        class="fa fa-th"></i> {{ trans('Scheduler::texts.calendar') }}</a>
        </li>
        <li><a href="{{ route('scheduler.create') }}"><i
                        class="fa fa-plus"></i> {{ trans('Scheduler::texts.create_event') }}</a>
        </li>
        <li><a href="{{ route('scheduler.tableevent') }}"><i
                        class="fa fa-table"></i> {{ trans('Scheduler::texts.table_event') }}</a>
        </li>
        <li><a href="{{ route('scheduler.tablerecurringevent') }}"><i
                        class="fa fa-refresh"></i> {{ trans('Scheduler::texts.recurring_event') }}</a>
        </li>
        <li class="treeview">
            <a href="#"><i class="fa fa-cogs fa-fw"></i>
                <span>{{ trans('Scheduler::texts.utilities') }}</span><i
                        class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
                <li class="treeview">
                    <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i>
                        <span>{{ trans('Scheduler::texts.report') }}</span><i
                                class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ route('scheduler.tablereport') }}">{{ trans('Scheduler::texts.table_report') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('scheduler.calendarreport') }}">{{ trans('Scheduler::texts.calendar_report') }}</a>
                        </li>
                    </ul>
                </li>
                <li><a href="{{ route('scheduler.eventtrash') }}"><i
                                class="fa fa-trash-o"></i> {{ trans('Scheduler::texts.trash') }}</a>
                </li>
                <li>
                    <a href="{{ route('scheduler.categories.index') }}"><i
                                class="fa fa-thumb-tack"></i>{{ trans('Scheduler::texts.categories') }}</a>
                </li>
                <li>
                    <a href="{{ route('scheduler.colors.index') }}"><i
                                class="fa fa-tint"></i>{{ trans('Scheduler::texts.colors') }}</a>
                </li>
                <li>
                    <a href="{{ route('scheduler.settings') }}"><i
                                class="fa fa-wrench"></i>{{ trans('Scheduler::texts.settings') }}</a>
                </li>

                <li><a href="{{ route('scheduler.about') }}"><i
                                class="fa fa-question-circle"></i> {{ trans('Scheduler::texts.about') }}
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</li>
