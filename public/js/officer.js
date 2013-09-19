/* =========================================================
 * bootstrap-datetimepicker.js
 * =========================================================
 * Copyright 2012 Stefan Petre
 * Improvements by Andrew Rowls
 * Improvements by Sébastien Malot
 * Improvements by Yun Lai
 * Project URL : http://www.malot.fr/bootstrap-datetimepicker
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */

!function( $ ) {

	function UTCDate(){
		return new Date(Date.UTC.apply(Date, arguments));
	}
	function UTCToday(){
		var today = new Date();
		return UTCDate(today.getUTCFullYear(), today.getUTCMonth(), today.getUTCDate(), today.getUTCHours(), today.getUTCMinutes(), today.getUTCSeconds(), 0);
	}

	// Picker object

	var Datetimepicker = function(element, options) {
		var that = this;

		this.element = $(element);
		this.language = options.language || this.element.data('date-language') || "en";
		this.language = this.language in dates ? this.language : "en";
		this.isRTL = dates[this.language].rtl || false;
		this.formatType = options.formatType || this.element.data('format-type') || 'standard';
		this.format = DPGlobal.parseFormat(options.format || this.element.data('date-format') || dates[this.language].format || DPGlobal.getDefaultFormat(this.formatType, 'input'), this.formatType);
		this.isInline = false;
		this.isVisible = false;
		this.isInput = this.element.is('input');
		this.component = this.element.is('.date') ? this.element.find('.add-on .icon-th, .add-on .icon-time, .add-on .icon-calendar').parent() : false;
		this.componentReset = this.element.is('.date') ? this.element.find('.add-on .icon-remove').parent() : false;
		this.hasInput = this.component && this.element.find('input').length;
		if (this.component && this.component.length === 0) {
			this.component = false;
		}
		this.linkField = options.linkField || this.element.data('link-field') || false;
		this.linkFormat = DPGlobal.parseFormat(options.linkFormat || this.element.data('link-format') || DPGlobal.getDefaultFormat(this.formatType, 'link'), this.formatType);
		this.minuteStep = options.minuteStep || this.element.data('minute-step') || 5;
		this.pickerPosition = options.pickerPosition || this.element.data('picker-position') || 'bottom-right';
				this.showMeridian = options.showMeridian || this.element.data('show-meridian') || false;
				this.initialDate = options.initialDate || new Date();

		this._attachEvents();

			this.formatViewType = "datetime";
			if ('formatViewType' in options) {
					this.formatViewType = options.formatViewType;
			} else if ('formatViewType' in this.element.data()) {
					this.formatViewType = this.element.data('formatViewType');
			}

		this.minView = 0;
		if ('minView' in options) {
			this.minView = options.minView;
		} else if ('minView' in this.element.data()) {
			this.minView = this.element.data('min-view');
		}
		this.minView = DPGlobal.convertViewMode(this.minView);

		this.maxView = DPGlobal.modes.length-1;
		if ('maxView' in options) {
			this.maxView = options.maxView;
		} else if ('maxView' in this.element.data()) {
			this.maxView = this.element.data('max-view');
		}
		this.maxView = DPGlobal.convertViewMode(this.maxView);

        this.wheelViewModeNavigation = false;
        if('wheelViewModeNavigation' in options){
            this.wheelViewModeNavigation = options.wheelViewModeNavigation;
        }else if('wheelViewModeNavigation' in this.element.data()){
            this.wheelViewModeNavigation = this.element.data('view-mode-wheel-navigation');
        }

        this.wheelViewModeNavigationInverseDirection = false;

        if('wheelViewModeNavigationInverseDirection' in options){
            this.wheelViewModeNavigationInverseDirection = options.wheelViewModeNavigationInverseDirection;
        }else if('wheelViewModeNavigationInverseDirection' in this.element.data()){
            this.wheelViewModeNavigationInverseDirection = this.element.data('view-mode-wheel-navigation-inverse-dir');
        }

        this.wheelViewModeNavigationDelay = 100;
        if('wheelViewModeNavigationDelay' in options){
            this.wheelViewModeNavigationDelay = options.wheelViewModeNavigationDelay;
        }else if('wheelViewModeNavigationDelay' in this.element.data()){
            this.wheelViewModeNavigationDelay = this.element.data('view-mode-wheel-navigation-delay');
        }

		this.startViewMode = 2;
		if ('startView' in options) {
			this.startViewMode = options.startView;
		} else if ('startView' in this.element.data()) {
			this.startViewMode = this.element.data('start-view');
		}
		this.startViewMode = DPGlobal.convertViewMode(this.startViewMode);
		this.viewMode = this.startViewMode;

				this.viewSelect = this.minView;
				if ('viewSelect' in options) {
						this.viewSelect = options.viewSelect;
				} else if ('viewSelect' in this.element.data()) {
						this.viewSelect = this.element.data('view-select');
				}
				this.viewSelect = DPGlobal.convertViewMode(this.viewSelect);

		this.forceParse = true;
		if ('forceParse' in options) {
			this.forceParse = options.forceParse;
		} else if ('dateForceParse' in this.element.data()) {
			this.forceParse = this.element.data('date-force-parse');
		}

		this.picker = $(DPGlobal.template)
							.appendTo(this.isInline ? this.element : 'body')
							.on({
								click: $.proxy(this.click, this),
								mousedown: $.proxy(this.mousedown, this)
							});

        if(this.wheelViewModeNavigation)
        {
            if($.fn.mousewheel)
            {
                this.picker.on({mousewheel: $.proxy(this.mousewheel,this)});
            }else
            {
                console.log("Mouse Wheel event is not supported. Please include the jQuery Mouse Wheel plugin before enabling this option");
            }
        }

		if (this.isInline) {
			this.picker.addClass('datetimepicker-inline');
		} else {
			this.picker.addClass('datetimepicker-dropdown-' + this.pickerPosition + ' dropdown-menu');
		}
		if (this.isRTL){
			this.picker.addClass('datetimepicker-rtl');
			this.picker.find('.prev i, .next i')
						.toggleClass('icon-arrow-left icon-arrow-right');
		}
		$(document).on('mousedown', function (e) {
			// Clicked outside the datetimepicker, hide it
			if ($(e.target).closest('.datetimepicker').length === 0) {
				that.hide();
			}
		});

		this.autoclose = false;
		if ('autoclose' in options) {
			this.autoclose = options.autoclose;
		} else if ('dateAutoclose' in this.element.data()) {
			this.autoclose = this.element.data('date-autoclose');
		}

		this.keyboardNavigation = true;
		if ('keyboardNavigation' in options) {
			this.keyboardNavigation = options.keyboardNavigation;
		} else if ('dateKeyboardNavigation' in this.element.data()) {
			this.keyboardNavigation = this.element.data('date-keyboard-navigation');
		}

		this.todayBtn = (options.todayBtn || this.element.data('date-today-btn') || false);
		this.todayHighlight = (options.todayHighlight || this.element.data('date-today-highlight') || false);

		this.weekStart = ((options.weekStart || this.element.data('date-weekstart') || dates[this.language].weekStart || 0) % 7);
		this.weekEnd = ((this.weekStart + 6) % 7);
		this.startDate = -Infinity;
		this.endDate = Infinity;
		this.daysOfWeekDisabled = [];
		this.setStartDate(options.startDate || this.element.data('date-startdate'));
		this.setEndDate(options.endDate || this.element.data('date-enddate'));
		this.setDaysOfWeekDisabled(options.daysOfWeekDisabled || this.element.data('date-days-of-week-disabled'));
		this.fillDow();
		this.fillMonths();
		this.update();
		this.showMode();

		if(this.isInline) {
			this.show();
		}
	};

	Datetimepicker.prototype = {
		constructor: Datetimepicker,

		_events: [],
		_attachEvents: function(){
			this._detachEvents();
			if (this.isInput) { // single input
				this._events = [
					[this.element, {
						focus: $.proxy(this.show, this),
						keyup: $.proxy(this.update, this),
						keydown: $.proxy(this.keydown, this)
					}]
				];
			}
			else if (this.component && this.hasInput){ // component: input + button
				this._events = [
					// For components that are not readonly, allow keyboard nav
					[this.element.find('input'), {
						focus: $.proxy(this.show, this),
						keyup: $.proxy(this.update, this),
						keydown: $.proxy(this.keydown, this)
					}],
					[this.component, {
						click: $.proxy(this.show, this)
					}]
				];
				if (this.componentReset) {
					this._events.push([
						this.componentReset,
						{click: $.proxy(this.reset, this)}
					]);
				}
			}
			else if (this.element.is('div')) {  // inline datetimepicker
				this.isInline = true;
			}
			else {
				this._events = [
					[this.element, {
						click: $.proxy(this.show, this)
					}]
				];
			}
			for (var i=0, el, ev; i<this._events.length; i++){
				el = this._events[i][0];
				ev = this._events[i][1];
				el.on(ev);
			}
		},

		_detachEvents: function(){
			for (var i=0, el, ev; i<this._events.length; i++){
				el = this._events[i][0];
				ev = this._events[i][1];
				el.off(ev);
			}
			this._events = [];
		},

		show: function(e) {
			this.picker.show();
			this.height = this.component ? this.component.outerHeight() : this.element.outerHeight();
			if (this.forceParse) {
				this.update();
			}
			this.place();
			$(window).on('resize', $.proxy(this.place, this));
			if (e) {
				e.stopPropagation();
				e.preventDefault();
			}
			this.isVisible = true;
			this.element.trigger({
				type: 'show',
				date: this.date
			});
		},

		hide: function(e){
			if(!this.isVisible) return;
			if(this.isInline) return;
			this.picker.hide();
			$(window).off('resize', this.place);
			this.viewMode = this.startViewMode;
			this.showMode();
			if (!this.isInput) {
				$(document).off('mousedown', this.hide);
			}

			if (
				this.forceParse &&
				(
					this.isInput && this.element.val()  ||
					this.hasInput && this.element.find('input').val()
				)
			)
				this.setValue();
			this.isVisible = false;
			this.element.trigger({
				type: 'hide',
				date: this.date
			});
		},

		remove: function() {
			this._detachEvents();
			this.picker.remove();
			delete this.picker;
			delete this.element.data().datetimepicker;
		},

		getDate: function() {
			var d = this.getUTCDate();
			return new Date(d.getTime() + (d.getTimezoneOffset()*60000));
		},

		getUTCDate: function() {
			return this.date;
		},

		setDate: function(d) {
			this.setUTCDate(new Date(d.getTime() - (d.getTimezoneOffset()*60000)));
		},

		setUTCDate: function(d) {
			if (d >= this.startDate && d <= this.endDate) {
				this.date = d;
				this.setValue();
				this.viewDate = this.date;
				this.fill();
			} else {
				this.element.trigger({
					type: 'outOfRange',
					date: d,
					startDate: this.startDate,
					endDate: this.endDate
				});
			}
		},

        setFormat: function(format) {
            this.format = DPGlobal.parseFormat(format, this.formatType);
            var element;
            if (this.isInput) {
                element = this.element;
            } else if (this.component){
                element = this.element.find('input');
            }
            if (element && element.val()) {
                this.setValue();
            }
        },

		setValue: function() {
			var formatted = this.getFormattedDate();
			if (!this.isInput) {
				if (this.component){
					this.element.find('input').val(formatted);
				}
				this.element.data('date', formatted);
			} else {
				this.element.val(formatted);
			}
			if (this.linkField) {
				$('#' + this.linkField).val(this.getFormattedDate(this.linkFormat));
			}
		},

		getFormattedDate: function(format) {
			if(format == undefined) format = this.format;
			return DPGlobal.formatDate(this.date, format, this.language, this.formatType);
		},

		setStartDate: function(startDate){
			this.startDate = startDate || -Infinity;
			if (this.startDate !== -Infinity) {
				this.startDate = DPGlobal.parseDate(this.startDate, this.format, this.language, this.formatType);
			}
			this.update();
			this.updateNavArrows();
		},

		setEndDate: function(endDate){
			this.endDate = endDate || Infinity;
			if (this.endDate !== Infinity) {
				this.endDate = DPGlobal.parseDate(this.endDate, this.format, this.language, this.formatType);
			}
			this.update();
			this.updateNavArrows();
		},

		setDaysOfWeekDisabled: function(daysOfWeekDisabled){
			this.daysOfWeekDisabled = daysOfWeekDisabled || [];
			if (!$.isArray(this.daysOfWeekDisabled)) {
				this.daysOfWeekDisabled = this.daysOfWeekDisabled.split(/,\s*/);
			}
			this.daysOfWeekDisabled = $.map(this.daysOfWeekDisabled, function (d) {
				return parseInt(d, 10);
			});
			this.update();
			this.updateNavArrows();
		},

		place: function(){
			if(this.isInline) return;
			var zIndex = parseInt(this.element.parents().filter(function() {
				return $(this).css('z-index') != 'auto';
			}).first().css('z-index'))+10;
			var offset, top, left;
			if (this.component) {
				offset = this.component.offset();
				left = offset.left;
				if (this.pickerPosition == 'bottom-left' || this.pickerPosition == 'top-left') {
					left += this.component.outerWidth() - this.picker.outerWidth();
				}
			} else {
				offset = this.element.offset();
				left = offset.left;
			}
			if (this.pickerPosition == 'top-left' || this.pickerPosition == 'top-right') {
				top = offset.top - this.picker.outerHeight();
			} else {
				top = offset.top + this.height;
			}
			this.picker.css({
				top: top,
				left: left,
				zIndex: zIndex
			});
		},

		update: function(){
			var date, fromArgs = false;
			if(arguments && arguments.length && (typeof arguments[0] === 'string' || arguments[0] instanceof Date)) {
				date = arguments[0];
				fromArgs = true;
			} else {
                date = this.element.data('date') || (this.isInput ? this.element.val() : this.element.find('input').val()) || this.initialDate;
			}

			if (!date) {
				date = new Date();
				fromArgs = false;
			}

			this.date = DPGlobal.parseDate(date, this.format, this.language, this.formatType);

			if (fromArgs) this.setValue();

			if (this.date < this.startDate) {
				this.viewDate = new Date(this.startDate);
			} else if (this.date > this.endDate) {
				this.viewDate = new Date(this.endDate);
			} else {
				this.viewDate = new Date(this.date);
			}
			this.fill();
		},

		fillDow: function(){
			var dowCnt = this.weekStart,
			html = '<tr>';
			while (dowCnt < this.weekStart + 7) {
				html += '<th class="dow">'+dates[this.language].daysMin[(dowCnt++)%7]+'</th>';
			}
			html += '</tr>';
			this.picker.find('.datetimepicker-days thead').append(html);
		},

		fillMonths: function(){
			var html = '',
			i = 0;
			while (i < 12) {
				html += '<span class="month">'+dates[this.language].monthsShort[i++]+'</span>';
			}
			this.picker.find('.datetimepicker-months td').html(html);
		},

		fill: function() {
			if (this.date == null || this.viewDate == null) {
				return;
			}
			var d = new Date(this.viewDate),
				year = d.getUTCFullYear(),
				month = d.getUTCMonth(),
				dayMonth = d.getUTCDate(),
				hours = d.getUTCHours(),
				minutes = d.getUTCMinutes(),
				startYear = this.startDate !== -Infinity ? this.startDate.getUTCFullYear() : -Infinity,
				startMonth = this.startDate !== -Infinity ? this.startDate.getUTCMonth() : -Infinity,
				endYear = this.endDate !== Infinity ? this.endDate.getUTCFullYear() : Infinity,
				endMonth = this.endDate !== Infinity ? this.endDate.getUTCMonth() : Infinity,
				currentDate = (new UTCDate(this.date.getUTCFullYear(), this.date.getUTCMonth(), this.date.getUTCDate())).valueOf(),
				today = new Date();
			this.picker.find('.datetimepicker-days thead th:eq(1)')
						.text(dates[this.language].months[month]+' '+year);
				if (this.formatViewType == "time") {
						var hourConverted = hours % 12 ? hours % 12 : 12;
						var hoursDisplay = (hourConverted < 10 ? '0' : '') + hourConverted;
						var minutesDisplay = (minutes < 10 ? '0' : '') + minutes;
						var meridianDisplay = dates[this.language].meridiem[hours < 12 ? 0 : 1];
						this.picker.find('.datetimepicker-hours thead th:eq(1)')
								.text(hoursDisplay + ':' + minutesDisplay + ' ' + meridianDisplay.toUpperCase());
						this.picker.find('.datetimepicker-minutes thead th:eq(1)')
								.text(hoursDisplay + ':' + minutesDisplay + ' ' + meridianDisplay.toUpperCase());
				} else {
						this.picker.find('.datetimepicker-hours thead th:eq(1)')
								.text(dayMonth + ' ' + dates[this.language].months[month] + ' ' + year);
						this.picker.find('.datetimepicker-minutes thead th:eq(1)')
								.text(dayMonth + ' ' + dates[this.language].months[month] + ' ' + year);
				}
				this.picker.find('tfoot th.today')
						.text(dates[this.language].today)
						.toggle(this.todayBtn !== false);
			this.updateNavArrows();
			this.fillMonths();
			/*var prevMonth = UTCDate(year, month, 0,0,0,0,0);
			prevMonth.setUTCDate(prevMonth.getDate() - (prevMonth.getUTCDay() - this.weekStart + 7)%7);*/
			var prevMonth = UTCDate(year, month-1, 28,0,0,0,0),
								day = DPGlobal.getDaysInMonth(prevMonth.getUTCFullYear(), prevMonth.getUTCMonth());
				prevMonth.setUTCDate(day);
					prevMonth.setUTCDate(day - (prevMonth.getUTCDay() - this.weekStart + 7)%7);
			var nextMonth = new Date(prevMonth);
			nextMonth.setUTCDate(nextMonth.getUTCDate() + 42);
			nextMonth = nextMonth.valueOf();
			var html = [];
			var clsName;
			while(prevMonth.valueOf() < nextMonth) {
				if (prevMonth.getUTCDay() == this.weekStart) {
					html.push('<tr>');
				}
				clsName = '';
				if (prevMonth.getUTCFullYear() < year || (prevMonth.getUTCFullYear() == year && prevMonth.getUTCMonth() < month)) {
					clsName += ' old';
				} else if (prevMonth.getUTCFullYear() > year || (prevMonth.getUTCFullYear() == year && prevMonth.getUTCMonth() > month)) {
					clsName += ' new';
				}
				// Compare internal UTC date with local today, not UTC today
				if (this.todayHighlight &&
					prevMonth.getUTCFullYear() == today.getFullYear() &&
					prevMonth.getUTCMonth() == today.getMonth() &&
					prevMonth.getUTCDate() == today.getDate()) {
					clsName += ' today';
				}
				if (prevMonth.valueOf() == currentDate) {
					clsName += ' active';
				}
				if ((prevMonth.valueOf() + 86400000) <= this.startDate || prevMonth.valueOf() > this.endDate ||
					$.inArray(prevMonth.getUTCDay(), this.daysOfWeekDisabled) !== -1) {
					clsName += ' disabled';
				}
				html.push('<td class="day'+clsName+'">'+prevMonth.getUTCDate() + '</td>');
				if (prevMonth.getUTCDay() == this.weekEnd) {
					html.push('</tr>');
				}
				prevMonth.setUTCDate(prevMonth.getUTCDate()+1);
			}
			this.picker.find('.datetimepicker-days tbody').empty().append(html.join(''));

			html = [];
						var txt = '', meridian = '', meridianOld = '';
			for (var i=0;i<24;i++) {
				var actual = UTCDate(year, month, dayMonth, i);
				clsName = '';
				// We want the previous hour for the startDate
				if ((actual.valueOf() + 3600000) <= this.startDate || actual.valueOf() > this.endDate) {
					clsName += ' disabled';
				} else if (hours == i) {
					clsName += ' active';
				}
								if (this.showMeridian && dates[this.language].meridiem.length == 2) {
										meridian = (i<12?dates[this.language].meridiem[0]:dates[this.language].meridiem[1]);
										if (meridian != meridianOld) {
												if (meridianOld != '') {
														html.push('</fieldset>');
												}
												html.push('<fieldset class="hour"><legend>'+meridian.toUpperCase()+'</legend>');
										}
										meridianOld = meridian;
										txt = (i%12?i%12:12);
										html.push('<span class="hour'+clsName+' hour_'+(i<12?'am':'pm')+'">'+txt+'</span>');
										if (i == 23) {
												html.push('</fieldset>');
										}
								} else {
										txt = i+':00';
										html.push('<span class="hour'+clsName+'">'+txt+'</span>');
								}
			}
			this.picker.find('.datetimepicker-hours td').html(html.join(''));

			html = [];
						txt = '', meridian = '', meridianOld = '';
			for(var i=0;i<60;i+=this.minuteStep) {
				var actual = UTCDate(year, month, dayMonth, hours, i, 0);
				clsName = '';
				if (actual.valueOf() < this.startDate || actual.valueOf() > this.endDate) {
					clsName += ' disabled';
				} else if (Math.floor(minutes/this.minuteStep) == Math.floor(i/this.minuteStep)) {
					clsName += ' active';
				}
								if (this.showMeridian && dates[this.language].meridiem.length == 2) {
										meridian = (hours<12?dates[this.language].meridiem[0]:dates[this.language].meridiem[1]);
										if (meridian != meridianOld) {
												if (meridianOld != '') {
														html.push('</fieldset>');
												}
												html.push('<fieldset class="minute"><legend>'+meridian.toUpperCase()+'</legend>');
										}
										meridianOld = meridian;
										txt = (hours%12?hours%12:12);
										//html.push('<span class="minute'+clsName+' minute_'+(hours<12?'am':'pm')+'">'+txt+'</span>');
										html.push('<span class="minute'+clsName+'">'+txt+':'+(i<10?'0'+i:i)+'</span>');
										if (i == 59) {
												html.push('</fieldset>');
										}
								} else {
										txt = i+':00';
										//html.push('<span class="hour'+clsName+'">'+txt+'</span>');
										html.push('<span class="minute'+clsName+'">'+hours+':'+(i<10?'0'+i:i)+'</span>');
								}
			}
			this.picker.find('.datetimepicker-minutes td').html(html.join(''));

			var currentYear = this.date.getUTCFullYear();
			var months = this.picker.find('.datetimepicker-months')
						.find('th:eq(1)')
						.text(year)
						.end()
						.find('span').removeClass('active');
			if (currentYear == year) {
				months.eq(this.date.getUTCMonth()).addClass('active');
			}
			if (year < startYear || year > endYear) {
				months.addClass('disabled');
			}
			if (year == startYear) {
				months.slice(0, startMonth).addClass('disabled');
			}
			if (year == endYear) {
				months.slice(endMonth+1).addClass('disabled');
			}

			html = '';
			year = parseInt(year/10, 10) * 10;
			var yearCont = this.picker.find('.datetimepicker-years')
								.find('th:eq(1)')
								.text(year + '-' + (year + 9))
								.end()
								.find('td');
			year -= 1;
			for (var i = -1; i < 11; i++) {
				html += '<span class="year'+(i == -1 || i == 10 ? ' old' : '')+(currentYear == year ? ' active' : '')+(year < startYear || year > endYear ? ' disabled' : '')+'">'+year+'</span>';
				year += 1;
			}
			yearCont.html(html);
			this.place();
		},

		updateNavArrows: function() {
			var d = new Date(this.viewDate),
				year = d.getUTCFullYear(),
				month = d.getUTCMonth(),
				day = d.getUTCDate(),
				hour = d.getUTCHours();
			switch (this.viewMode) {
				case 0:
					if (this.startDate !== -Infinity && year <= this.startDate.getUTCFullYear()
													 && month <= this.startDate.getUTCMonth()
													 && day <= this.startDate.getUTCDate()
													 && hour <= this.startDate.getUTCHours()) {
						this.picker.find('.prev').css({visibility: 'hidden'});
					} else {
						this.picker.find('.prev').css({visibility: 'visible'});
					}
					if (this.endDate !== Infinity && year >= this.endDate.getUTCFullYear()
													&& month >= this.endDate.getUTCMonth()
													&& day >= this.endDate.getUTCDate()
													&& hour >= this.endDate.getUTCHours()) {
						this.picker.find('.next').css({visibility: 'hidden'});
					} else {
						this.picker.find('.next').css({visibility: 'visible'});
					}
					break;
				case 1:
					if (this.startDate !== -Infinity && year <= this.startDate.getUTCFullYear()
													 && month <= this.startDate.getUTCMonth()
													 && day <= this.startDate.getUTCDate()) {
						this.picker.find('.prev').css({visibility: 'hidden'});
					} else {
						this.picker.find('.prev').css({visibility: 'visible'});
					}
					if (this.endDate !== Infinity && year >= this.endDate.getUTCFullYear()
													&& month >= this.endDate.getUTCMonth()
													&& day >= this.endDate.getUTCDate()) {
						this.picker.find('.next').css({visibility: 'hidden'});
					} else {
						this.picker.find('.next').css({visibility: 'visible'});
					}
					break;
				case 2:
					if (this.startDate !== -Infinity && year <= this.startDate.getUTCFullYear()
													 && month <= this.startDate.getUTCMonth()) {
						this.picker.find('.prev').css({visibility: 'hidden'});
					} else {
						this.picker.find('.prev').css({visibility: 'visible'});
					}
					if (this.endDate !== Infinity && year >= this.endDate.getUTCFullYear()
													&& month >= this.endDate.getUTCMonth()) {
						this.picker.find('.next').css({visibility: 'hidden'});
					} else {
						this.picker.find('.next').css({visibility: 'visible'});
					}
					break;
				case 3:
				case 4:
					if (this.startDate !== -Infinity && year <= this.startDate.getUTCFullYear()) {
						this.picker.find('.prev').css({visibility: 'hidden'});
					} else {
						this.picker.find('.prev').css({visibility: 'visible'});
					}
					if (this.endDate !== Infinity && year >= this.endDate.getUTCFullYear()) {
						this.picker.find('.next').css({visibility: 'hidden'});
					} else {
						this.picker.find('.next').css({visibility: 'visible'});
					}
					break;
			}
		},

        mousewheel: function(e){

            e.preventDefault();
            e.stopPropagation();

            if(this.wheelPause)
            {
                return;
            }

            this.wheelPause = true;

            var originalEvent = e.originalEvent;

            var delta = originalEvent.wheelDelta;

            var mode = delta > 0 ? 1:(delta === 0)?0:-1;

            if(this.wheelViewModeNavigationInverseDirection)
            {
                mode = -mode;
            }

            this.showMode(mode);

            setTimeout($.proxy(function(){

                this.wheelPause = false

            },this),this.wheelViewModeNavigationDelay);



        },

		click: function(e) {
			e.stopPropagation();
			e.preventDefault();
			var target = $(e.target).closest('span, td, th, legend');
			if (target.length == 1) {
				if (target.is('.disabled')) {
					this.element.trigger({
						type: 'outOfRange',
						date: this.viewDate,
						startDate: this.startDate,
						endDate: this.endDate
					});
					return;
				}
				switch(target[0].nodeName.toLowerCase()) {
					case 'th':
						switch(target[0].className) {
							case 'switch':
								this.showMode(1);
								break;
							case 'prev':
							case 'next':
								var dir = DPGlobal.modes[this.viewMode].navStep * (target[0].className == 'prev' ? -1 : 1);
								switch(this.viewMode){
									case 0:
										this.viewDate = this.moveHour(this.viewDate, dir);
										break;
									case 1:
										this.viewDate = this.moveDate(this.viewDate, dir);
										break;
									case 2:
										this.viewDate = this.moveMonth(this.viewDate, dir);
										break;
									case 3:
									case 4:
										this.viewDate = this.moveYear(this.viewDate, dir);
										break;
								}
								this.fill();
								break;
							case 'today':
								var date = new Date();
								date = UTCDate(date.getFullYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes(), date.getSeconds(), 0);

								this.viewMode = this.startViewMode;
								this.showMode(0);
								this._setDate(date);
								this.fill();
								if (this.autoclose) {
									this.hide();
								}
								break;
						}
						break;
					case 'span':
						if (!target.is('.disabled')) {
														var year    = this.viewDate.getUTCFullYear(),
																month   = this.viewDate.getUTCMonth(),
																day     = this.viewDate.getUTCDate(),
																hours   = this.viewDate.getUTCHours(),
																minutes = this.viewDate.getUTCMinutes(),
																seconds = this.viewDate.getUTCSeconds();

							if (target.is('.month')) {
								this.viewDate.setUTCDate(1);
								month = target.parent().find('span').index(target);
																day   = this.viewDate.getUTCDate();
								this.viewDate.setUTCMonth(month);
								this.element.trigger({
									type: 'changeMonth',
									date: this.viewDate
								});
																if (this.viewSelect >= 3) {
										this._setDate(UTCDate(year, month, day, hours, minutes, seconds, 0));
																}
							} else if (target.is('.year')) {
								this.viewDate.setUTCDate(1);
								year = parseInt(target.text(), 10) || 0;
								this.viewDate.setUTCFullYear(year);
								this.element.trigger({
									type: 'changeYear',
									date: this.viewDate
								});
																if (this.viewSelect >= 4) {
																		this._setDate(UTCDate(year, month, day, hours, minutes, seconds, 0));
																}
							} else if (target.is('.hour')){
								hours = parseInt(target.text(), 10) || 0;
																if (target.hasClass('hour_am') || target.hasClass('hour_pm')) {
																		if (hours == 12 && target.hasClass('hour_am')) {
																				hours = 0;
																		} else if (hours != 12 && target.hasClass('hour_pm')) {
																				hours += 12;
																		}
																}
																this.viewDate.setUTCHours(hours);
								this.element.trigger({
									type: 'changeHour',
									date: this.viewDate
								});
																if (this.viewSelect >= 1) {
										this._setDate(UTCDate(year, month, day, hours, minutes, seconds, 0));
																}
							} else if (target.is('.minute')){
								minutes = parseInt(target.text().substr(target.text().indexOf(':')+1), 10) || 0;
																this.viewDate.setUTCMinutes(minutes);
								this.element.trigger({
									type: 'changeMinute',
									date: this.viewDate
								});
																if (this.viewSelect >= 0) {
										this._setDate(UTCDate(year, month, day, hours, minutes, seconds, 0));
																}
							}
							if (this.viewMode != 0) {
								var oldViewMode = this.viewMode;
								this.showMode(-1);
								this.fill();
								if (oldViewMode == this.viewMode && this.autoclose) {
									this.hide();
								}
							} else {
								this.fill();
								if (this.autoclose) {
									this.hide();
								}
							}
						}
						break;
					case 'td':
						if (target.is('.day') && !target.is('.disabled')){
							var day = parseInt(target.text(), 10) || 1;
							var year = this.viewDate.getUTCFullYear(),
								month = this.viewDate.getUTCMonth(),
								hours = this.viewDate.getUTCHours(),
								minutes = this.viewDate.getUTCMinutes(),
								seconds = this.viewDate.getUTCSeconds();
							if (target.is('.old')) {
								if (month === 0) {
									month = 11;
									year -= 1;
								} else {
									month -= 1;
								}
							} else if (target.is('.new')) {
								if (month == 11) {
									month = 0;
									year += 1;
								} else {
									month += 1;
								}
							}
														this.viewDate.setUTCFullYear(year);
														this.viewDate.setUTCMonth(month);
														this.viewDate.setUTCDate(day);
														this.element.trigger({
																type: 'changeDay',
																date: this.viewDate
														});
														if (this.viewSelect >= 2) {
									this._setDate(UTCDate(year, month, day, hours, minutes, seconds, 0));
														}
						}
						var oldViewMode = this.viewMode;
						this.showMode(-1);
						this.fill();
						if (oldViewMode == this.viewMode && this.autoclose) {
							this.hide();
						}
						break;
				}
			}
		},

		_setDate: function(date, which){
			if (!which || which == 'date')
				this.date = date;
			if (!which || which  == 'view')
				this.viewDate = date;
			this.fill();
			this.setValue();
			var element;
			if (this.isInput) {
				element = this.element;
			} else if (this.component){
				element = this.element.find('input');
			}
			if (element) {
				element.change();
				if (this.autoclose && (!which || which == 'date')) {
					//this.hide();
				}
			}
			this.element.trigger({
				type: 'changeDate',
				date: this.date
			});
		},

		moveMinute: function(date, dir){
			if (!dir) return date;
			var new_date = new Date(date.valueOf());
			//dir = dir > 0 ? 1 : -1;
			new_date.setUTCMinutes(new_date.getUTCMinutes() + (dir * this.minuteStep));
			return new_date;
		},

		moveHour: function(date, dir){
			if (!dir) return date;
			var new_date = new Date(date.valueOf());
			//dir = dir > 0 ? 1 : -1;
			new_date.setUTCHours(new_date.getUTCHours() + dir);
			return new_date;
		},

		moveDate: function(date, dir){
			if (!dir) return date;
			var new_date = new Date(date.valueOf());
			//dir = dir > 0 ? 1 : -1;
			new_date.setUTCDate(new_date.getUTCDate() + dir);
			return new_date;
		},

		moveMonth: function(date, dir){
			if (!dir) return date;
			var new_date = new Date(date.valueOf()),
				day = new_date.getUTCDate(),
				month = new_date.getUTCMonth(),
				mag = Math.abs(dir),
				new_month, test;
			dir = dir > 0 ? 1 : -1;
			if (mag == 1){
				test = dir == -1
					// If going back one month, make sure month is not current month
					// (eg, Mar 31 -> Feb 31 == Feb 28, not Mar 02)
					? function(){ return new_date.getUTCMonth() == month; }
					// If going forward one month, make sure month is as expected
					// (eg, Jan 31 -> Feb 31 == Feb 28, not Mar 02)
					: function(){ return new_date.getUTCMonth() != new_month; };
				new_month = month + dir;
				new_date.setUTCMonth(new_month);
				// Dec -> Jan (12) or Jan -> Dec (-1) -- limit expected date to 0-11
				if (new_month < 0 || new_month > 11)
					new_month = (new_month + 12) % 12;
			} else {
				// For magnitudes >1, move one month at a time...
				for (var i=0; i<mag; i++)
					// ...which might decrease the day (eg, Jan 31 to Feb 28, etc)...
					new_date = this.moveMonth(new_date, dir);
				// ...then reset the day, keeping it in the new month
				new_month = new_date.getUTCMonth();
				new_date.setUTCDate(day);
				test = function(){ return new_month != new_date.getUTCMonth(); };
			}
			// Common date-resetting loop -- if date is beyond end of month, make it
			// end of month
			while (test()){
				new_date.setUTCDate(--day);
				new_date.setUTCMonth(new_month);
			}
			return new_date;
		},

		moveYear: function(date, dir){
			return this.moveMonth(date, dir*12);
		},

		dateWithinRange: function(date){
			return date >= this.startDate && date <= this.endDate;
		},

		keydown: function(e){
			if (this.picker.is(':not(:visible)')){
				if (e.keyCode == 27) // allow escape to hide and re-show picker
					this.show();
				return;
			}
			var dateChanged = false,
				dir, day, month,
				newDate, newViewDate;
			switch(e.keyCode){
				case 27: // escape
					this.hide();
					e.preventDefault();
					break;
				case 37: // left
				case 39: // right
					if (!this.keyboardNavigation) break;
					dir = e.keyCode == 37 ? -1 : 1;
										viewMode = this.viewMode;
										if (e.ctrlKey) {
												viewMode += 2;
										} else if (e.shiftKey) {
												viewMode += 1;
										}
										if (viewMode == 4) {
						newDate = this.moveYear(this.date, dir);
						newViewDate = this.moveYear(this.viewDate, dir);
										} else if (viewMode == 3) {
						newDate = this.moveMonth(this.date, dir);
						newViewDate = this.moveMonth(this.viewDate, dir);
										} else if (viewMode == 2) {
						newDate = this.moveDate(this.date, dir);
						newViewDate = this.moveDate(this.viewDate, dir);
										} else if (viewMode == 1) {
						newDate = this.moveHour(this.date, dir);
						newViewDate = this.moveHour(this.viewDate, dir);
										} else if (viewMode == 0) {
						newDate = this.moveMinute(this.date, dir);
						newViewDate = this.moveMinute(this.viewDate, dir);
										}
					if (this.dateWithinRange(newDate)){
						this.date = newDate;
						this.viewDate = newViewDate;
						this.setValue();
						this.update();
						e.preventDefault();
						dateChanged = true;
					}
					break;
				case 38: // up
				case 40: // down
					if (!this.keyboardNavigation) break;
					dir = e.keyCode == 38 ? -1 : 1;
										viewMode = this.viewMode;
										if (e.ctrlKey) {
												viewMode += 2;
										} else if (e.shiftKey) {
												viewMode += 1;
										}
										if (viewMode == 4) {
						newDate = this.moveYear(this.date, dir);
						newViewDate = this.moveYear(this.viewDate, dir);
										} else if (viewMode == 3) {
						newDate = this.moveMonth(this.date, dir);
						newViewDate = this.moveMonth(this.viewDate, dir);
										} else if (viewMode == 2) {
						newDate = this.moveDate(this.date, dir * 7);
						newViewDate = this.moveDate(this.viewDate, dir * 7);
										} else if (viewMode == 1) {
												if (this.showMeridian) {
														newDate = this.moveHour(this.date, dir * 6);
														newViewDate = this.moveHour(this.viewDate, dir * 6);
												} else {
														newDate = this.moveHour(this.date, dir * 4);
														newViewDate = this.moveHour(this.viewDate, dir * 4);
												}
										} else if (viewMode == 0) {
						newDate = this.moveMinute(this.date, dir * 4);
						newViewDate = this.moveMinute(this.viewDate, dir * 4);
										}
					if (this.dateWithinRange(newDate)){
						this.date = newDate;
						this.viewDate = newViewDate;
						this.setValue();
						this.update();
						e.preventDefault();
						dateChanged = true;
					}
					break;
				case 13: // enter
										if (this.viewMode != 0) {
												var oldViewMode = this.viewMode;
												this.showMode(-1);
												this.fill();
												if (oldViewMode == this.viewMode && this.autoclose) {
														this.hide();
												}
										} else {
												this.fill();
												if (this.autoclose) {
									this.hide();
												}
										}
					e.preventDefault();
					break;
				case 9: // tab
					this.hide();
					break;
			}
			if (dateChanged){
				var element;
				if (this.isInput) {
					element = this.element;
				} else if (this.component){
					element = this.element.find('input');
				}
				if (element) {
					element.change();
				}
				this.element.trigger({
					type: 'changeDate',
					date: this.date
				});
			}
		},

		showMode: function(dir) {
			if (dir) {
				var newViewMode = Math.max(0, Math.min(DPGlobal.modes.length - 1, this.viewMode + dir));
				if (newViewMode >= this.minView && newViewMode <= this.maxView) {
					this.element.trigger({
						type: 'changeMode',
						date: this.viewDate,
						oldViewMode: this.viewMode,
						newViewMode: newViewMode
					});

					this.viewMode = newViewMode;
				}
			}
			/*
				vitalets: fixing bug of very special conditions:
				jquery 1.7.1 + webkit + show inline datetimepicker in bootstrap popover.
				Method show() does not set display css correctly and datetimepicker is not shown.
				Changed to .css('display', 'block') solve the problem.
				See https://github.com/vitalets/x-editable/issues/37

				In jquery 1.7.2+ everything works fine.
			*/
			//this.picker.find('>div').hide().filter('.datetimepicker-'+DPGlobal.modes[this.viewMode].clsName).show();
			this.picker.find('>div').hide().filter('.datetimepicker-'+DPGlobal.modes[this.viewMode].clsName).css('display', 'block');
			this.updateNavArrows();
		},

		reset: function(e) {
			this._setDate(null, 'date');
		}
	};

	$.fn.datetimepicker = function ( option ) {
		var args = Array.apply(null, arguments);
		args.shift();
		return this.each(function () {
			var $this = $(this),
				data = $this.data('datetimepicker'),
				options = typeof option == 'object' && option;
			if (!data) {
				$this.data('datetimepicker', (data = new Datetimepicker(this, $.extend({}, $.fn.datetimepicker.defaults,options))));
			}
			if (typeof option == 'string' && typeof data[option] == 'function') {
				data[option].apply(data, args);
			}
		});
	};

	$.fn.datetimepicker.defaults = {
	};
	$.fn.datetimepicker.Constructor = Datetimepicker;
	var dates = $.fn.datetimepicker.dates = {
		en: {
			days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
			daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
			daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
			months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
			monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			meridiem: ["am", "pm"],
			suffix: ["st", "nd", "rd", "th"],
			today: "Today"
		}
	};

	var DPGlobal = {
		modes: [
			{
				clsName: 'minutes',
				navFnc: 'Hours',
				navStep: 1
			},
			{
				clsName: 'hours',
				navFnc: 'Date',
				navStep: 1
			},
			{
				clsName: 'days',
				navFnc: 'Month',
				navStep: 1
			},
			{
				clsName: 'months',
				navFnc: 'FullYear',
				navStep: 1
			},
			{
				clsName: 'years',
				navFnc: 'FullYear',
				navStep: 10
		}],
		isLeapYear: function (year) {
			return (((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0))
		},
		getDaysInMonth: function (year, month) {
			return [31, (DPGlobal.isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month]
		},
		getDefaultFormat: function (type, field) {
			if (type == "standard") {
				if (field == 'input')
					return 'yyyy-mm-dd hh:ii';
				else
					return 'yyyy-mm-dd hh:ii:ss';
			} else if (type == "php") {
				if (field == 'input')
					return 'Y-m-d H:i';
				else
					return 'Y-m-d H:i:s';
			} else {
				throw new Error("Invalid format type.");
			}
		},
		validParts: function (type) {
			if (type == "standard") {
				return /hh?|HH?|p|P|ii?|ss?|dd?|DD?|mm?|MM?|yy(?:yy)?/g;
			} else if (type == "php") {
				return /[dDjlNwzFmMnStyYaABgGhHis]/g;
			} else {
				throw new Error("Invalid format type.");
			}
		},
		nonpunctuation: /[^ -\/:-@\[-`{-~\t\n\rTZ]+/g,
		parseFormat: function(format, type){
			// IE treats \0 as a string end in inputs (truncating the value),
			// so it's a bad format delimiter, anyway
			var separators = format.replace(this.validParts(type), '\0').split('\0'),
				parts = format.match(this.validParts(type));
			if (!separators || !separators.length || !parts || parts.length == 0){
				throw new Error("Invalid date format.");
			}
			return {separators: separators, parts: parts};
		},
		parseDate: function(date, format, language, type) {
			if (date instanceof Date) {
				var dateUTC = new Date(date.valueOf() - date.getTimezoneOffset() * 60000);
								dateUTC.setMilliseconds(0);
				return dateUTC;
			}
			if (/^\d{4}\-\d{1,2}\-\d{1,2}$/.test(date)) {
				format = this.parseFormat('yyyy-mm-dd', type);
			}
			if (/^\d{4}\-\d{1,2}\-\d{1,2}[T ]\d{1,2}\:\d{1,2}$/.test(date)) {
				format = this.parseFormat('yyyy-mm-dd hh:ii', type);
			}
			if (/^\d{4}\-\d{1,2}\-\d{1,2}[T ]\d{1,2}\:\d{1,2}\:\d{1,2}[Z]{0,1}$/.test(date)) {
				format = this.parseFormat('yyyy-mm-dd hh:ii:ss', type);
			}
			if (/^[-+]\d+[dmwy]([\s,]+[-+]\d+[dmwy])*$/.test(date)) {
				var part_re = /([-+]\d+)([dmwy])/,
					parts = date.match(/([-+]\d+)([dmwy])/g),
					part, dir;
				date = new Date();
				for (var i=0; i<parts.length; i++) {
					part = part_re.exec(parts[i]);
					dir = parseInt(part[1]);
					switch(part[2]){
						case 'd':
							date.setUTCDate(date.getUTCDate() + dir);
							break;
						case 'm':
							date = Datetimepicker.prototype.moveMonth.call(Datetimepicker.prototype, date, dir);
							break;
						case 'w':
							date.setUTCDate(date.getUTCDate() + dir * 7);
							break;
						case 'y':
							date = Datetimepicker.prototype.moveYear.call(Datetimepicker.prototype, date, dir);
							break;
					}
				}
				return UTCDate(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(), date.getUTCHours(), date.getUTCMinutes(), date.getUTCSeconds(), 0);
			}
			var parts = date && date.match(this.nonpunctuation) || [],
				date = new Date(0, 0, 0, 0, 0, 0, 0),
				parsed = {},
				setters_order = ['hh', 'h', 'ii', 'i', 'ss', 's', 'yyyy', 'yy', 'M', 'MM', 'm', 'mm', 'D', 'DD', 'd', 'dd', 'H', 'HH', 'p', 'P'],
				setters_map = {
					hh: function(d,v){ return d.setUTCHours(v); },
					h:  function(d,v){ return d.setUTCHours(v); },
					HH: function(d,v){ return d.setUTCHours(v==12?0:v); },
					H:  function(d,v){ return d.setUTCHours(v==12?0:v); },
					ii: function(d,v){ return d.setUTCMinutes(v); },
					i:  function(d,v){ return d.setUTCMinutes(v); },
					ss: function(d,v){ return d.setUTCSeconds(v); },
					s:  function(d,v){ return d.setUTCSeconds(v); },
					yyyy: function(d,v){ return d.setUTCFullYear(v); },
					yy: function(d,v){ return d.setUTCFullYear(2000+v); },
					m: function(d,v){
						v -= 1;
						while (v<0) v += 12;
						v %= 12;
						d.setUTCMonth(v);
						while (d.getUTCMonth() != v)
							d.setUTCDate(d.getUTCDate()-1);
						return d;
					},
					d: function(d,v){ return d.setUTCDate(v); },
					p: function(d,v){ return d.setUTCHours(v==1?d.getUTCHours()+12:d.getUTCHours()); }
				},
				val, filtered, part;
			setters_map['M'] = setters_map['MM'] = setters_map['mm'] = setters_map['m'];
			setters_map['dd'] = setters_map['d'];
					setters_map['P'] = setters_map['p'];
			date = UTCDate(date.getFullYear(), date.getMonth(), date.getDate(), date.getHours(), date.getMinutes(), date.getSeconds());
			if (parts.length == format.parts.length) {
				for (var i=0, cnt = format.parts.length; i < cnt; i++) {
					val = parseInt(parts[i], 10);
					part = format.parts[i];
					if (isNaN(val)) {
						switch(part) {
							case 'MM':
								filtered = $(dates[language].months).filter(function(){
									var m = this.slice(0, parts[i].length),
										p = parts[i].slice(0, m.length);
									return m == p;
								});
								val = $.inArray(filtered[0], dates[language].months) + 1;
								break;
							case 'M':
								filtered = $(dates[language].monthsShort).filter(function(){
									var m = this.slice(0, parts[i].length),
										p = parts[i].slice(0, m.length);
									return m == p;
								});
								val = $.inArray(filtered[0], dates[language].monthsShort) + 1;
								break;
								case 'p':
								case 'P':
										val = $.inArray(parts[i].toLowerCase(), dates[language].meridiem);
										break;
						}
					}
					parsed[part] = val;
				}
				for (var i=0, s; i<setters_order.length; i++){
					s = setters_order[i];
					if (s in parsed && !isNaN(parsed[s]))
						setters_map[s](date, parsed[s])
				}
			}
			return date;
		},
		formatDate: function(date, format, language, type){
			if (date == null) {
				return '';
			}
			var val;
			if (type == 'standard') {
				val = {
					// year
					yy: date.getUTCFullYear().toString().substring(2),
					yyyy: date.getUTCFullYear(),
					// month
					m: date.getUTCMonth() + 1,
					M: dates[language].monthsShort[date.getUTCMonth()],
					MM: dates[language].months[date.getUTCMonth()],
					// day
					d: date.getUTCDate(),
					D: dates[language].daysShort[date.getUTCDay()],
					DD: dates[language].days[date.getUTCDay()],
					p: (dates[language].meridiem.length==2?dates[language].meridiem[date.getUTCHours()<12?0:1]:''),
					// hour
					h: date.getUTCHours(),
					// minute
					i: date.getUTCMinutes(),
					// second
					s: date.getUTCSeconds()
				};

        if (dates[language].meridiem.length==2) {
          val.H  = (val.h%12==0? 12 : val.h%12);
        }
        else {
          val.H  = val.h;
        }
								val.HH = (val.H < 10 ? '0' : '') + val.H;
								val.P  = val.p.toUpperCase();
				val.hh = (val.h < 10 ? '0' : '') + val.h;
				val.ii = (val.i < 10 ? '0' : '') + val.i;
				val.ss = (val.s < 10 ? '0' : '') + val.s;
				val.dd = (val.d < 10 ? '0' : '') + val.d;
				val.mm = (val.m < 10 ? '0' : '') + val.m;
			} else if (type == 'php') {
				// php format
				val = {
					// year
					y: date.getUTCFullYear().toString().substring(2),
					Y: date.getUTCFullYear(),
					// month
					F: dates[language].months[date.getUTCMonth()],
					M: dates[language].monthsShort[date.getUTCMonth()],
					n: date.getUTCMonth() + 1,
					t: DPGlobal.getDaysInMonth(date.getUTCFullYear(), date.getUTCMonth()),
					// day
					j: date.getUTCDate(),
					l: dates[language].days[date.getUTCDay()],
					D: dates[language].daysShort[date.getUTCDay()],
					w: date.getUTCDay(), // 0 -> 6
					N: (date.getUTCDay()==0?7:date.getUTCDay()),       // 1 -> 7
					S: (date.getUTCDate()%10<=dates[language].suffix.length?dates[language].suffix[date.getUTCDate()%10-1]:''),
					// hour
					a: (dates[language].meridiem.length==2?dates[language].meridiem[date.getUTCHours()<12?0:1]:''),
					g: (date.getUTCHours()%12==0?12:date.getUTCHours()%12),
					G: date.getUTCHours(),
					// minute
					i: date.getUTCMinutes(),
					// second
					s: date.getUTCSeconds()
				};
				val.m = (val.n < 10 ? '0' : '') + val.n;
				val.d = (val.j < 10 ? '0' : '') + val.j;
				val.A = val.a.toString().toUpperCase();
				val.h = (val.g < 10 ? '0' : '') + val.g;
				val.H = (val.G < 10 ? '0' : '') + val.G;
				val.i = (val.i < 10 ? '0' : '') + val.i;
				val.s = (val.s < 10 ? '0' : '') + val.s;
			} else {
				throw new Error("Invalid format type.");
			}
			var date = [],
				seps = $.extend([], format.separators);
			for (var i=0, cnt = format.parts.length; i < cnt; i++) {
				if (seps.length)
					date.push(seps.shift())
				date.push(val[format.parts[i]]);
			}
			return date.join('');
		},
		convertViewMode: function(viewMode){
			switch (viewMode) {
				case 4:
				case 'decade':
					viewMode = 4;
					break;
				case 3:
				case 'year':
					viewMode = 3;
					break;
				case 2:
				case 'month':
					viewMode = 2;
					break;
				case 1:
				case 'day':
					viewMode = 1;
					break;
				case 0:
				case 'hour':
					viewMode = 0;
					break;
			}

			return viewMode;
		},
		headTemplate: '<thead>'+
							'<tr>'+
								'<th class="prev"><i class="icon-arrow-left"/></th>'+
								'<th colspan="5" class="switch"></th>'+
								'<th class="next"><i class="icon-arrow-right"/></th>'+
							'</tr>'+
						'</thead>',
		contTemplate: '<tbody><tr><td colspan="7"></td></tr></tbody>',
		footTemplate: '<tfoot><tr><th colspan="7" class="today"></th></tr></tfoot>'
	};
	DPGlobal.template = '<div class="datetimepicker">'+
							'<div class="datetimepicker-minutes">'+
								'<table class=" table-condensed">'+
									DPGlobal.headTemplate+
									DPGlobal.contTemplate+
									DPGlobal.footTemplate+
								'</table>'+
							'</div>'+
							'<div class="datetimepicker-hours">'+
								'<table class=" table-condensed">'+
									DPGlobal.headTemplate+
									DPGlobal.contTemplate+
									DPGlobal.footTemplate+
								'</table>'+
							'</div>'+
							'<div class="datetimepicker-days">'+
								'<table class=" table-condensed">'+
									DPGlobal.headTemplate+
									'<tbody></tbody>'+
									DPGlobal.footTemplate+
								'</table>'+
							'</div>'+
							'<div class="datetimepicker-months">'+
								'<table class="table-condensed">'+
									DPGlobal.headTemplate+
									DPGlobal.contTemplate+
									DPGlobal.footTemplate+
								'</table>'+
							'</div>'+
							'<div class="datetimepicker-years">'+
								'<table class="table-condensed">'+
									DPGlobal.headTemplate+
									DPGlobal.contTemplate+
									DPGlobal.footTemplate+
								'</table>'+
							'</div>'+
						'</div>';

	$.fn.datetimepicker.DPGlobal = DPGlobal;


	/* DATETIMEPICKER NO CONFLICT
	* =================== */

	$.fn.datetimepicker.noConflict = function () {
	    $.fn.datetimepicker = old;
	    return this;
	};


	/* DATETIMEPICKER DATA-API
	* ================== */

	$(document).on(
		'focus.datetimepicker.data-api click.datetimepicker.data-api',
		'[data-provide="datetimepicker"]',
		function (e) {
		    var $this = $(this);
		    if ($this.data('datetimepicker')) return;
		    e.preventDefault();
		    // component click requires us to explicitly show it
		    $this.datetimepicker('show');
		}
	);
	$(function () {
	    $('[data-provide="datetimepicker-inline"]').datetimepicker();
	});

}( window.jQuery );

/* =========================================================
 * bootstrap-datepicker.js
 * http://www.eyecon.ro/bootstrap-datepicker
 * =========================================================
 * Copyright 2012 Stefan Petre
 * Improvements by Andrew Rowls
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */

!function( $ ) {

  function UTCDate(){
    return new Date(Date.UTC.apply(Date, arguments));
  }
  function UTCToday(){
    var today = new Date();
    return UTCDate(today.getUTCFullYear(), today.getUTCMonth(), today.getUTCDate());
  }

  // Picker object

  var Datepicker = function(element, options) {
    var that = this;

    this.element = $(element);
    this.language = options.language||this.element.data('date-language')||"en";
    this.language = this.language in dates ? this.language : "en";
    this.format = DPGlobal.parseFormat(options.format||this.element.data('date-format')||'m/d/yy');
    this.picker = $(DPGlobal.template)
              .appendTo('body')
              .on({
                click: $.proxy(this.click, this)
              });
    this.isInput = this.element.is('input');
    this.component = this.element.is('.date') ? this.element.find('.add-on') : false;
    this.hasInput = this.component && this.element.find('input').length;
    if(this.component && this.component.length === 0)
      this.component = false;

    if (this.isInput) {
      this.element.on({
        focus: $.proxy(this.show, this),
        keyup: $.proxy(this.update, this),
        keydown: $.proxy(this.keydown, this)
      });
    } else {
      if (this.component && this.hasInput){
        // For components that are not readonly, allow keyboard nav
        this.element.find('input').on({
          focus: $.proxy(this.show, this),
          keyup: $.proxy(this.update, this),
          keydown: $.proxy(this.keydown, this)
        });

        this.component.on('click', $.proxy(this.show, this));
      } else {
        this.element.on('click', $.proxy(this.show, this));
      }
    }

    $(document).on('mousedown', function (e) {
      // Clicked outside the datepicker, hide it
      if ($(e.target).closest('.datepicker').length == 0) {
        that.hide();
      }
    });

    this.autoclose = true;
    if ('autoclose' in options) {
      this.autoclose = options.autoclose;
    } else if ('dateAutoclose' in this.element.data()) {
      this.autoclose = this.element.data('date-autoclose');
    }

    this.keyboardNavigation = true;
    if ('keyboardNavigation' in options) {
      this.keyboardNavigation = options.keyboardNavigation;
    } else if ('dateKeyboardNavigation' in this.element.data()) {
      this.keyboardNavigation = this.element.data('date-keyboard-navigation');
    }

    switch(options.startView || this.element.data('date-start-view')){
      case 2:
      case 'decade':
        this.viewMode = this.startViewMode = 2;
        break;
      case 1:
      case 'year':
        this.viewMode = this.startViewMode = 1;
        break;
      case 0:
      case 'month':
      default:
        this.viewMode = this.startViewMode = 0;
        break;
    }

    this.todayBtn = (options.todayBtn||this.element.data('date-today-btn')||false);
    this.todayHighlight = (options.todayHighlight||this.element.data('date-today-highlight')||true);

    this.weekStart = ((options.weekStart||this.element.data('date-weekstart')||dates[this.language].weekStart||0) % 7);
    this.weekEnd = ((this.weekStart + 6) % 7);
    this.startDate = -Infinity;
    this.endDate = Infinity;
    this.setStartDate(options.startDate||this.element.data('date-startdate'));
    this.setEndDate(options.endDate||this.element.data('date-enddate'));
    this.fillDow();
    this.fillMonths();
    this.update();
    this.showMode();
  };

  Datepicker.prototype = {
    constructor: Datepicker,

    show: function(e) {
      this.picker.show();
      this.height = this.component ? this.component.outerHeight() : this.element.outerHeight();
      this.update();
      this.place();
      $(window).on('resize', $.proxy(this.place, this));
      if (e ) {
        e.stopPropagation();
        e.preventDefault();
      }
      this.element.trigger({
        type: 'show',
        date: this.date
      });
    },

    hide: function(e){
      this.picker.hide();
      $(window).off('resize', this.place);
      this.viewMode = this.startViewMode;
      this.showMode();
      if (!this.isInput) {
        $(document).off('mousedown', this.hide);
      }
      if (e && e.currentTarget.value)
        this.setValue();
      this.element.trigger({
        type: 'hide',
        date: this.date
      });
    },

    getDate: function() {
      var d = this.getUTCDate();
      return new Date(d.getTime() + (d.getTimezoneOffset()*60000))
    },

    getUTCDate: function() {
      return this.date;
    },

    setDate: function(d) {
      this.setUTCDate(new Date(d.getTime() - (d.getTimezoneOffset()*60000)));
    },

    setUTCDate: function(d) {
      this.date = d;
      this.setValue();
    },

    setValue: function() {
      var formatted = DPGlobal.formatDate(this.date, this.format, this.language);
      if (!this.isInput) {
        if (this.component){
          this.element.find('input').prop('value', formatted);
        }
        this.element.data('date', formatted);
      } else {
        this.element.prop('value', formatted);
      }
    },

    setStartDate: function(startDate){
      this.startDate = startDate||-Infinity;
      if (this.startDate !== -Infinity) {
        this.startDate = DPGlobal.parseDate(this.startDate, this.format, this.language);
      }
      this.update();
      this.updateNavArrows();
    },

    setEndDate: function(endDate){
      this.endDate = endDate||Infinity;
      if (this.endDate !== Infinity) {
        this.endDate = DPGlobal.parseDate(this.endDate, this.format, this.language);
      }
      this.update();
      this.updateNavArrows();
    },

    place: function(){
      var zIndex = parseInt(this.element.parents().filter(function() {
              return $(this).css('z-index') != 'auto';
            }).first().css('z-index'))+10;
      var offset = this.component ? this.component.offset() : this.element.offset();
      this.picker.css({
        top: offset.top + this.height,
        left: offset.left,
        zIndex: zIndex
      });
    },

    update: function(){
      this.date = DPGlobal.parseDate(
        this.isInput ? this.element.prop('value') : this.element.data('date') || this.element.find('input').prop('value'),
        this.format, this.language
      );
      if (this.date < this.startDate) {
        this.viewDate = new Date(this.startDate);
      } else if (this.date > this.endDate) {
        this.viewDate = new Date(this.endDate);
      } else {
        this.viewDate = new Date(this.date);
      }
      this.fill();
    },

    fillDow: function(){
      var dowCnt = this.weekStart;
      var html = '<tr>';
      while (dowCnt < this.weekStart + 7) {
        html += '<th class="dow">'+dates[this.language].daysMin[(dowCnt++)%7]+'</th>';
      }
      html += '</tr>';
      this.picker.find('.datepicker-days thead').append(html);
    },

    fillMonths: function(){
      var html = '';
      var i = 0
      while (i < 12) {
        html += '<span class="month">'+dates[this.language].monthsShort[i++]+'</span>';
      }
      this.picker.find('.datepicker-months td').html(html);
    },

    fill: function() {
      var d = new Date(this.viewDate),
        year = d.getUTCFullYear(),
        month = d.getUTCMonth(),
        startYear = this.startDate !== -Infinity ? this.startDate.getUTCFullYear() : -Infinity,
        startMonth = this.startDate !== -Infinity ? this.startDate.getUTCMonth() : -Infinity,
        endYear = this.endDate !== Infinity ? this.endDate.getUTCFullYear() : Infinity,
        endMonth = this.endDate !== Infinity ? this.endDate.getUTCMonth() : Infinity,
        currentDate = this.date.valueOf(),
        today = new Date();
      this.picker.find('.datepicker-days thead th:eq(1)')
            .text(dates[this.language].months[month]+' '+year);
      this.picker.find('tfoot th.today')
            .text(dates[this.language].today)
            .toggle(this.todayBtn);
      this.updateNavArrows();
      this.fillMonths();
      var prevMonth = UTCDate(year, month-1, 28,0,0,0,0),
        day = DPGlobal.getDaysInMonth(prevMonth.getUTCFullYear(), prevMonth.getUTCMonth());
      prevMonth.setUTCDate(day);
      prevMonth.setUTCDate(day - (prevMonth.getUTCDay() - this.weekStart + 7)%7);
      var nextMonth = new Date(prevMonth);
      nextMonth.setUTCDate(nextMonth.getUTCDate() + 42);
      nextMonth = nextMonth.valueOf();
      var html = [];
      var clsName;
      while(prevMonth.valueOf() < nextMonth) {
        if (prevMonth.getUTCDay() == this.weekStart) {
          html.push('<tr>');
        }
        clsName = '';
        if (prevMonth.getUTCFullYear() < year || (prevMonth.getUTCFullYear() == year && prevMonth.getUTCMonth() < month)) {
          clsName += ' old';
        } else if (prevMonth.getUTCFullYear() > year || (prevMonth.getUTCFullYear() == year && prevMonth.getUTCMonth() > month)) {
          clsName += ' new';
        }
        // Compare internal UTC date with local today, not UTC today
        if (this.todayHighlight &&
          prevMonth.getUTCFullYear() == today.getFullYear() &&
          prevMonth.getUTCMonth() == today.getMonth() &&
          prevMonth.getUTCDate() == today.getDate()) {
          clsName += ' today';
        }
        if (prevMonth.valueOf() == currentDate) {
          clsName += ' active';
        }
        if (prevMonth.valueOf() < this.startDate || prevMonth.valueOf() > this.endDate) {
          clsName += ' disabled';
        }
        html.push('<td class="day'+clsName+'">'+prevMonth.getUTCDate() + '</td>');
        if (prevMonth.getUTCDay() == this.weekEnd) {
          html.push('</tr>');
        }
        prevMonth.setUTCDate(prevMonth.getUTCDate()+1);
      }
      this.picker.find('.datepicker-days tbody').empty().append(html.join(''));
      var currentYear = this.date.getUTCFullYear();

      var months = this.picker.find('.datepicker-months')
            .find('th:eq(1)')
              .text(year)
              .end()
            .find('span').removeClass('active');
      if (currentYear == year) {
        months.eq(this.date.getUTCMonth()).addClass('active');
      }
      if (year < startYear || year > endYear) {
        months.addClass('disabled');
      }
      if (year == startYear) {
        months.slice(0, startMonth).addClass('disabled');
      }
      if (year == endYear) {
        months.slice(endMonth+1).addClass('disabled');
      }

      html = '';
      year = parseInt(year/10, 10) * 10;
      var yearCont = this.picker.find('.datepicker-years')
                .find('th:eq(1)')
                  .text(year + '-' + (year + 9))
                  .end()
                .find('td');
      year -= 1;
      for (var i = -1; i < 11; i++) {
        html += '<span class="year'+(i == -1 || i == 10 ? ' old' : '')+(currentYear == year ? ' active' : '')+(year < startYear || year > endYear ? ' disabled' : '')+'">'+year+'</span>';
        year += 1;
      }
      yearCont.html(html);
    },

    updateNavArrows: function() {
      var d = new Date(this.viewDate),
        year = d.getUTCFullYear(),
        month = d.getUTCMonth();
      switch (this.viewMode) {
        case 0:
          if (this.startDate !== -Infinity && year <= this.startDate.getUTCFullYear() && month <= this.startDate.getUTCMonth()) {
            this.picker.find('.prev').css({visibility: 'hidden'});
          } else {
            this.picker.find('.prev').css({visibility: 'visible'});
          }
          if (this.endDate !== Infinity && year >= this.endDate.getUTCFullYear() && month >= this.endDate.getUTCMonth()) {
            this.picker.find('.next').css({visibility: 'hidden'});
          } else {
            this.picker.find('.next').css({visibility: 'visible'});
          }
          break;
        case 1:
        case 2:
          if (this.startDate !== -Infinity && year <= this.startDate.getUTCFullYear()) {
            this.picker.find('.prev').css({visibility: 'hidden'});
          } else {
            this.picker.find('.prev').css({visibility: 'visible'});
          }
          if (this.endDate !== Infinity && year >= this.endDate.getUTCFullYear()) {
            this.picker.find('.next').css({visibility: 'hidden'});
          } else {
            this.picker.find('.next').css({visibility: 'visible'});
          }
          break;
      }
    },

    click: function(e) {
      e.stopPropagation();
      e.preventDefault();
      var target = $(e.target).closest('span, td, th');
      if (target.length == 1) {
        switch(target[0].nodeName.toLowerCase()) {
          case 'th':
            switch(target[0].className) {
              case 'switch':
                this.showMode(1);
                break;
              case 'prev':
              case 'next':
                var dir = DPGlobal.modes[this.viewMode].navStep * (target[0].className == 'prev' ? -1 : 1);
                switch(this.viewMode){
                  case 0:
                    this.viewDate = this.moveMonth(this.viewDate, dir);
                    break;
                  case 1:
                  case 2:
                    this.viewDate = this.moveYear(this.viewDate, dir);
                    break;
                }
                this.fill();
                break;
              case 'today':
                var date = new Date();
                date.setUTCHours(0);
                date.setUTCMinutes(0);
                date.setUTCSeconds(0);
                date.setUTCMilliseconds(0);

                this.showMode(-2);
                var which = this.todayBtn == 'linked' ? null : 'view';
                this._setDate(date, which);
                break;
            }
            break;
          case 'span':
            if (!target.is('.disabled')) {
              this.viewDate.setUTCDate(1);
              if (target.is('.month')) {
                var month = target.parent().find('span').index(target);
                this.viewDate.setUTCMonth(month);
                this.element.trigger({
                  type: 'changeMonth',
                  date: this.viewDate
                });
              } else {
                var year = parseInt(target.text(), 10)||0;
                this.viewDate.setUTCFullYear(year);
                this.element.trigger({
                  type: 'changeYear',
                  date: this.viewDate
                });
              }
              this.showMode(-1);
              this.fill();
            }
            break;
          case 'td':
            if (target.is('.day') && !target.is('.disabled')){
              var day = parseInt(target.text(), 10)||1;
              var year = this.viewDate.getUTCFullYear(),
                month = this.viewDate.getUTCMonth();
              if (target.is('.old')) {
                if (month == 0) {
                  month = 11;
                  year -= 1;
                } else {
                  month -= 1;
                }
              } else if (target.is('.new')) {
                if (month == 11) {
                  month = 0;
                  year += 1;
                } else {
                  month += 1;
                }
              }
              this._setDate(UTCDate(year, month, day,0,0,0,0));
            }
            break;
        }
      }
    },

    _setDate: function(date, which){
      if (!which || which == 'date')
        this.date = date;
      if (!which || which  == 'view')
        this.viewDate = date;
      this.fill();
      this.setValue();
      this.element.trigger({
        type: 'changeDate',
        date: this.date
      });
      var element;
      if (this.isInput) {
        element = this.element;
      } else if (this.component){
        element = this.element.find('input');
      }
      if (element) {
        element.change();
        if (this.autoclose) {
                  this.hide();
        }
      }
    },

    moveMonth: function(date, dir){
      if (!dir) return date;
      var new_date = new Date(date.valueOf()),
        day = new_date.getUTCDate(),
        month = new_date.getUTCMonth(),
        mag = Math.abs(dir),
        new_month, test;
      dir = dir > 0 ? 1 : -1;
      if (mag == 1){
        test = dir == -1
          // If going back one month, make sure month is not current month
          // (eg, Mar 31 -> Feb 31 == Feb 28, not Mar 02)
          ? function(){ return new_date.getUTCMonth() == month; }
          // If going forward one month, make sure month is as expected
          // (eg, Jan 31 -> Feb 31 == Feb 28, not Mar 02)
          : function(){ return new_date.getUTCMonth() != new_month; };
        new_month = month + dir;
        new_date.setUTCMonth(new_month);
        // Dec -> Jan (12) or Jan -> Dec (-1) -- limit expected date to 0-11
        if (new_month < 0 || new_month > 11)
          new_month = (new_month + 12) % 12;
      } else {
        // For magnitudes >1, move one month at a time...
        for (var i=0; i<mag; i++)
          // ...which might decrease the day (eg, Jan 31 to Feb 28, etc)...
          new_date = this.moveMonth(new_date, dir);
        // ...then reset the day, keeping it in the new month
        new_month = new_date.getUTCMonth();
        new_date.setUTCDate(day);
        test = function(){ return new_month != new_date.getUTCMonth(); };
      }
      // Common date-resetting loop -- if date is beyond end of month, make it
      // end of month
      while (test()){
        new_date.setUTCDate(--day);
        new_date.setUTCMonth(new_month);
      }
      return new_date;
    },

    moveYear: function(date, dir){
      return this.moveMonth(date, dir*12);
    },

    dateWithinRange: function(date){
      return date >= this.startDate && date <= this.endDate;
    },

    keydown: function(e){
      if (this.picker.is(':not(:visible)')){
        if (e.keyCode == 27) // allow escape to hide and re-show picker
          this.show();
        return;
      }
      var dateChanged = false,
        dir, day, month,
        newDate, newViewDate;
      switch(e.keyCode){
        case 27: // escape
          this.hide();
          e.preventDefault();
          break;
        case 37: // left
        case 39: // right
          if (!this.keyboardNavigation) break;
          dir = e.keyCode == 37 ? -1 : 1;
          if (e.ctrlKey){
            newDate = this.moveYear(this.date, dir);
            newViewDate = this.moveYear(this.viewDate, dir);
          } else if (e.shiftKey){
            newDate = this.moveMonth(this.date, dir);
            newViewDate = this.moveMonth(this.viewDate, dir);
          } else {
            newDate = new Date(this.date);
            newDate.setUTCDate(this.date.getUTCDate() + dir);
            newViewDate = new Date(this.viewDate);
            newViewDate.setUTCDate(this.viewDate.getUTCDate() + dir);
          }
          if (this.dateWithinRange(newDate)){
            this.date = newDate;
            this.viewDate = newViewDate;
            this.setValue();
            this.update();
            e.preventDefault();
            dateChanged = true;
          }
          break;
        case 38: // up
        case 40: // down
          if (!this.keyboardNavigation) break;
          dir = e.keyCode == 38 ? -1 : 1;
          if (e.ctrlKey){
            newDate = this.moveYear(this.date, dir);
            newViewDate = this.moveYear(this.viewDate, dir);
          } else if (e.shiftKey){
            newDate = this.moveMonth(this.date, dir);
            newViewDate = this.moveMonth(this.viewDate, dir);
          } else {
            newDate = new Date(this.date);
            newDate.setUTCDate(this.date.getUTCDate() + dir * 7);
            newViewDate = new Date(this.viewDate);
            newViewDate.setUTCDate(this.viewDate.getUTCDate() + dir * 7);
          }
          if (this.dateWithinRange(newDate)){
            this.date = newDate;
            this.viewDate = newViewDate;
            this.setValue();
            this.update();
            e.preventDefault();
            dateChanged = true;
          }
          break;
        case 13: // enter
          this.hide();
          e.preventDefault();
          break;
        case 9: // tab
          this.hide();
          break;
      }
      if (dateChanged){
        this.element.trigger({
          type: 'changeDate',
          date: this.date
        });
        var element;
        if (this.isInput) {
          element = this.element;
        } else if (this.component){
          element = this.element.find('input');
        }
        if (element) {
          element.change();
        }
      }
    },

    showMode: function(dir) {
      if (dir) {
        this.viewMode = Math.max(0, Math.min(2, this.viewMode + dir));
      }
      this.picker.find('>div').hide().filter('.datepicker-'+DPGlobal.modes[this.viewMode].clsName).show();
      this.updateNavArrows();
    }
  };

  $.fn.datepicker = function ( option ) {
    var args = Array.apply(null, arguments);
    args.shift();
    return this.each(function () {
      var $this = $(this),
        data = $this.data('datepicker'),
        options = typeof option == 'object' && option;
      if (!data) {
        $this.data('datepicker', (data = new Datepicker(this, $.extend({}, $.fn.datepicker.defaults,options))));
      }
      if (typeof option == 'string' && typeof data[option] == 'function') {
        data[option].apply(data, args);
      }
    });
  };

  $.fn.datepicker.defaults = {
  };
  $.fn.datepicker.Constructor = Datepicker;
  var dates = $.fn.datepicker.dates = {
    en: {
      days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
      daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
      daysMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"],
      months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
      monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
      today: "Today"
    }
  }

  var DPGlobal = {
    modes: [
      {
        clsName: 'days',
        navFnc: 'Month',
        navStep: 1
      },
      {
        clsName: 'months',
        navFnc: 'FullYear',
        navStep: 1
      },
      {
        clsName: 'years',
        navFnc: 'FullYear',
        navStep: 10
    }],
    isLeapYear: function (year) {
      return (((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0))
    },
    getDaysInMonth: function (year, month) {
      return [31, (DPGlobal.isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month]
    },
    validParts: /dd?|mm?|MM?|yy(?:yy)?/g,
    nonpunctuation: /[^ -\/:-@\[-`{-~\t\n\r]+/g,
    parseFormat: function(format){
      // IE treats \0 as a string end in inputs (truncating the value),
      // so it's a bad format delimiter, anyway
      var separators = format.replace(this.validParts, '\0').split('\0'),
        parts = format.match(this.validParts);
      if (!separators || !separators.length || !parts || parts.length == 0){
        throw new Error("Invalid date format.");
      }
      return {separators: separators, parts: parts};
    },
    parseDate: function(date, format, language) {
      if (date instanceof Date) return date;
      if (/^[-+]\d+[dmwy]([\s,]+[-+]\d+[dmwy])*$/.test(date)) {
        var part_re = /([-+]\d+)([dmwy])/,
          parts = date.match(/([-+]\d+)([dmwy])/g),
          part, dir;
        date = new Date();
        for (var i=0; i<parts.length; i++) {
          part = part_re.exec(parts[i]);
          dir = parseInt(part[1]);
          switch(part[2]){
            case 'd':
              date.setUTCDate(date.getUTCDate() + dir);
              break;
            case 'm':
              date = Datepicker.prototype.moveMonth.call(Datepicker.prototype, date, dir);
              break;
            case 'w':
              date.setUTCDate(date.getUTCDate() + dir * 7);
              break;
            case 'y':
              date = Datepicker.prototype.moveYear.call(Datepicker.prototype, date, dir);
              break;
          }
        }
        return UTCDate(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(), 0, 0, 0);
      }
      var parts = date && date.match(this.nonpunctuation) || [],
        date = new Date(),
        parsed = {},
        setters_order = ['yyyy', 'yy', 'M', 'MM', 'm', 'mm', 'd', 'dd'],
        setters_map = {
          yyyy: function(d,v){ return d.setUTCFullYear(v); },
          yy: function(d,v){ return d.setUTCFullYear(2000+v); },
          m: function(d,v){
            v -= 1;
            while (v<0) v += 12;
            v %= 12;
            d.setUTCMonth(v);
            while (d.getUTCMonth() != v)
              d.setUTCDate(d.getUTCDate()-1);
            return d;
          },
          d: function(d,v){ return d.setUTCDate(v); }
        },
        val, filtered, part;
      setters_map['M'] = setters_map['MM'] = setters_map['mm'] = setters_map['m'];
      setters_map['dd'] = setters_map['d'];
      date = UTCDate(date.getUTCFullYear(), date.getUTCMonth(), date.getUTCDate(), 0, 0, 0);
      if (parts.length == format.parts.length) {
        for (var i=0, cnt = format.parts.length; i < cnt; i++) {
          val = parseInt(parts[i], 10);
          part = format.parts[i];
          if (isNaN(val)) {
            switch(part) {
              case 'MM':
                filtered = $(dates[language].months).filter(function(){
                  var m = this.slice(0, parts[i].length),
                    p = parts[i].slice(0, m.length);
                  return m == p;
                });
                val = $.inArray(filtered[0], dates[language].months) + 1;
                break;
              case 'M':
                filtered = $(dates[language].monthsShort).filter(function(){
                  var m = this.slice(0, parts[i].length),
                    p = parts[i].slice(0, m.length);
                  return m == p;
                });
                val = $.inArray(filtered[0], dates[language].monthsShort) + 1;
                break;
            }
          }
          parsed[part] = val;
        }
        for (var i=0, s; i<setters_order.length; i++){
          s = setters_order[i];
          if (s in parsed)
            setters_map[s](date, parsed[s])
        }
      }
      return date;
    },
    formatDate: function(date, format, language){
      var val = {
        d: date.getUTCDate(),
        m: date.getUTCMonth() + 1,
        M: dates[language].monthsShort[date.getUTCMonth()],
        MM: dates[language].months[date.getUTCMonth()],
        yy: date.getUTCFullYear().toString().substring(2),
        yyyy: date.getUTCFullYear()
      };
      val.dd = (val.d < 10 ? '0' : '') + val.d;
      val.mm = (val.m < 10 ? '0' : '') + val.m;
      var date = [],
        seps = $.extend([], format.separators);
      for (var i=0, cnt = format.parts.length; i < cnt; i++) {
        if (seps.length)
          date.push(seps.shift())
        date.push(val[format.parts[i]]);
      }
      return date.join('');
    },
    headTemplate: '<thead>'+
              '<tr>'+
                '<th class="prev"><i class="icon-arrow-left"/></th>'+
                '<th colspan="5" class="switch"></th>'+
                '<th class="next"><i class="icon-arrow-right"/></th>'+
              '</tr>'+
            '</thead>',
    contTemplate: '<tbody><tr><td colspan="7"></td></tr></tbody>',
    footTemplate: '<tfoot><tr><th colspan="7" class="today"></th></tr></tfoot>'
  };
  DPGlobal.template = '<div class="datepicker dropdown-menu">'+
              '<div class="datepicker-days">'+
                '<table class=" table-condensed">'+
                  DPGlobal.headTemplate+
                  '<tbody></tbody>'+
                  DPGlobal.footTemplate+
                '</table>'+
              '</div>'+
              '<div class="datepicker-months">'+
                '<table class="table-condensed">'+
                  DPGlobal.headTemplate+
                  DPGlobal.contTemplate+
                  DPGlobal.footTemplate+
                '</table>'+
              '</div>'+
              '<div class="datepicker-years">'+
                '<table class="table-condensed">'+
                  DPGlobal.headTemplate+
                  DPGlobal.contTemplate+
                  DPGlobal.footTemplate+
                '</table>'+
              '</div>'+
            '</div>';
}( window.jQuery );
/*
 * HTML5 Sortable jQuery Plugin
 * http://farhadi.ir/projects/html5sortable
 *
 * Copyright 2012, Ali Farhadi
 * Released under the MIT license.
 */
(function($) {
var dragging, placeholders = $();
$.fn.sortable = function(options) {
  var method = String(options);
  options = $.extend({
    connectWith: false
  }, options);
  return this.each(function() {
    if (/^enable|disable|destroy$/.test(method)) {
      var items = $(this).children($(this).data('items')).attr('draggable', method == 'enable');
      if (method == 'destroy') {
        items.add(this).removeData('connectWith items')
          .off('dragstart.h5s dragend.h5s selectstart.h5s dragover.h5s dragenter.h5s drop.h5s');
      }
      return;
    }
    var isHandle, index, items = $(this).children(options.items);
    var placeholder = $('<' + (/^ul|ol$/i.test(this.tagName) ? 'li' : 'div') + ' class="sortable-placeholder">');
    items.find(options.handle).mousedown(function() {
      isHandle = true;
    }).mouseup(function() {
      isHandle = false;
    });
    $(this).data('items', options.items)
    placeholders = placeholders.add(placeholder);
    if (options.connectWith) {
      $(options.connectWith).add(this).data('connectWith', options.connectWith);
    }
    items.attr('draggable', 'true').on('dragstart.h5s', function(e) {
      if (options.handle && !isHandle) {
        return false;
      }
      isHandle = false;
      var dt = e.originalEvent.dataTransfer;
      dt.effectAllowed = 'move';
      dt.setData('Text', 'dummy');
      index = (dragging = $(this)).addClass('sortable-dragging').index();
      e.stopPropagation();
    }).on('dragend.h5s', function() {
      if (!dragging) {
        return;
      }
      dragging.removeClass('sortable-dragging').show();
      placeholders.detach();
      if (index != dragging.index()) {
        dragging.parent().trigger('sortupdate', {item: dragging});
      }
      dragging = null;
    }).not('a[href], img').on('selectstart.h5s', function() {
      this.dragDrop && this.dragDrop();
      return false;
    }).end().add([this, placeholder]).on('dragover.h5s dragenter.h5s drop.h5s', function(e) {
      if (!items.is(dragging) && options.connectWith !== $(dragging).parent().data('connectWith')) {
        return true;
      }
      if (e.type == 'drop') {
        e.stopPropagation();
        placeholders.filter(':visible').after(dragging);
        dragging.trigger('dragend.h5s');
        return false;
      }
      e.preventDefault();
      e.originalEvent.dataTransfer.dropEffect = 'move';
      if (items.is(this)) {
        if (options.forcePlaceholderSize) {
          placeholder.height(dragging.outerHeight());
        }
        dragging.hide();
        $(this)[placeholder.index() < $(this).index() ? 'after' : 'before'](placeholder);
        placeholders.not(placeholder).detach();
      } else if (!placeholders.is(this) && !$(this).children(options.items).length) {
        placeholders.detach();
        $(this).append(placeholder);
      }
      return false;
    });
  });
};
})(jQuery);

// Generated by CoffeeScript 1.3.3
(function(){(function(e){var t;t=["font","letter-spacing"];return e.fn.autoGrow=function(n){var r,i,s;i=n==="remove"||n===!1||(n!=null?!!n.remove:!!void 0);r=(s=n!=null?n.comfortZone:void 0)!=null?s:n;r!=null&&(r=+r);return this.each(function(){var n,s,o,u,a,f,l,c,h,p;o=e(this);f=o.next().filter("pre.autogrow");if(f.length&&i){o.unbind("input.autogrow");return f.remove()}if(f.length){a={};for(l=0,h=t.length;l<h;l++){u=t[l];a[u]=o.css(u)}f.css(a);if(r!=null){n=function(){f.text(o.val());return o.width(f.width()+r)};o.unbind("input.autogrow");o.bind("input.autogrow",n);return n()}}else if(!i){o.css("min-width")==="0px"&&o.css("min-width",""+o.width()+"px");a={position:"absolute",top:-99999,left:-99999,width:"auto",visibility:"hidden"};for(c=0,p=t.length;c<p;c++){u=t[c];a[u]=o.css(u)}f=e('<pre class="autogrow"/>').css(a);f.insertAfter(o);s=r!=null?r:70;n=function(){f.text(o.val());return o.width(f.width()+s)};o.bind("input.autogrow",n);return n()}})}})(typeof Zepto!="undefined"&&Zepto!==null?Zepto:jQuery)}).call(this);
/*
 * jQuery Hotkeys Plugin
 * Copyright 2010, John Resig
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Based upon the plugin by Tzury Bar Yochay:
 * http://github.com/tzuryby/hotkeys
 *
 * Original idea by:
 * Binny V A, http://www.openjs.com/scripts/events/keyboard_shortcuts/
*/

(function(jQuery){

  jQuery.hotkeys = {
    version: "0.8",

    specialKeys: {
      8: "backspace", 9: "tab", 13: "return", 16: "shift", 17: "ctrl", 18: "alt", 19: "pause",
      20: "capslock", 27: "esc", 32: "space", 33: "pageup", 34: "pagedown", 35: "end", 36: "home",
      37: "left", 38: "up", 39: "right", 40: "down", 45: "insert", 46: "del",
      96: "0", 97: "1", 98: "2", 99: "3", 100: "4", 101: "5", 102: "6", 103: "7",
      104: "8", 105: "9", 106: "*", 107: "+", 109: "-", 110: ".", 111 : "/",
      112: "f1", 113: "f2", 114: "f3", 115: "f4", 116: "f5", 117: "f6", 118: "f7", 119: "f8",
      120: "f9", 121: "f10", 122: "f11", 123: "f12", 144: "numlock", 145: "scroll", 191: "/", 224: "meta"
    },

    shiftNums: {
      "`": "~", "1": "!", "2": "@", "3": "#", "4": "$", "5": "%", "6": "^", "7": "&",
      "8": "*", "9": "(", "0": ")", "-": "_", "=": "+", ";": ": ", "'": "\"", ",": "<",
      ".": ">",  "/": "?",  "\\": "|"
    }
  };

  function keyHandler( handleObj ) {
    // Only care when a possible input has been specified
    if ( typeof handleObj.data !== "string" ) {
      return;
    }

    var origHandler = handleObj.handler,
      keys = handleObj.data.toLowerCase().split(" ");

    handleObj.handler = function( event ) {
      // Don't fire in text-accepting inputs that we didn't directly bind to
      if ( this !== event.target && (/textarea|select|button/i.test( event.target.nodeName ) ||
         event.target.type === "text") ) {
        return;
      }

      // Keypress represents characters, not special keys
      var special = event.type !== "keypress" && jQuery.hotkeys.specialKeys[ event.which ],
        character = String.fromCharCode( event.which ).toLowerCase(),
        key, modif = "", possible = {};

      // check combinations (alt|ctrl|shift+anything)
      if ( event.altKey && special !== "alt" ) {
        modif += "alt+";
      }

      if ( event.ctrlKey && special !== "ctrl" ) {
        modif += "ctrl+";
      }

      // TODO: Need to make sure this works consistently across platforms
      if ( event.metaKey && !event.ctrlKey && special !== "meta" ) {
        modif += "meta+";
      }

      if ( event.shiftKey && special !== "shift" ) {
        modif += "shift+";
      }

      if ( special ) {
        possible[ modif + special ] = true;

      } else {
        possible[ modif + character ] = true;
        possible[ modif + jQuery.hotkeys.shiftNums[ character ] ] = true;

        // "$" can be triggered as "Shift+4" or "Shift+$" or just "$"
        if ( modif === "shift+" ) {
          possible[ jQuery.hotkeys.shiftNums[ character ] ] = true;
        }
      }

      for ( var i = 0, l = keys.length; i < l; i++ ) {
        if ( possible[ keys[i] ] ) {
          return origHandler.apply( this, arguments );
        }
      }
    };
  }

  jQuery.each([ "keydown", "keyup", "keypress" ], function() {
    jQuery.event.special[ this ] = { add: keyHandler };
  });

})( jQuery );
$(document).on("ready page:load", function() {
  var typeahead_searching;
  typeahead_searching = false;
  return $("#add-collaborator-form input[name=email]").typeahead({
    minLength: 3,
    source: function(query, process) {
      clearTimeout(typeahead_searching);
      return typeahead_searching = setTimeout(function() {
        var existing_collaborators;
        existing_collaborators = [];
        $(".collaborators-table td.email").each(function() {
          return existing_collaborators.push($(this).text());
        });
        return $.ajax({
          url: "/officers/typeahead",
          data: {
            query: query
          },
          success: function(data) {
            data = $.grep(data, function(value) {
              return existing_collaborators.indexOf(value) === -1;
            });
            return process(data);
          }
        });
      }, 200);
    }
  });
});

var add_empty_class_to_inputs, apply_section_cover, available_sections_filter_timeout, hide_already_selected_sections, remove_section_cover, save_sort_order, search_available_sections, section_category_dropdown_changed, update_section_category_dropdown_from_input;

hide_already_selected_sections = function() {
  var selected_section_ids, showing_at_least_one_section;
  selected_section_ids = [];
  showing_at_least_one_section = false;
  $(".sections-for-editing .section").each(function() {
    return selected_section_ids.push($(this).data('section-id'));
  });
  $(".available-sections-table .section").each(function() {
    var el, section_id;
    el = $(this);
    section_id = el.data('section-id');
    if (selected_section_ids.indexOf(section_id) !== -1) {
      return el.hide();
    } else {
      showing_at_least_one_section = true;
      return el.show();
    }
  });
  if (!showing_at_least_one_section) {
    return $(".available-sections-table .no-sections").show();
  } else {
    return $(".available-sections-table .no-sections").hide();
  }
};

apply_section_cover = function() {
  var cover, sections_wrapper;
  cover = $("<div class='sections-for-editing-cover'>Saving order...</div>");
  sections_wrapper = $(".sections-for-editing-wrapper");
  cover.css({
    width: sections_wrapper.width(),
    height: sections_wrapper.height()
  });
  return cover.appendTo(sections_wrapper);
};

remove_section_cover = function() {
  return $(".sections-for-editing-cover").remove();
};

save_sort_order = function() {
  var project_id, sections;
  apply_section_cover();
  project_id = $(".sections-for-editing-wrapper").data('project-id');
  sections = [];
  $(".sections-for-editing-wrapper .section").each(function() {
    return sections.push($(this).data('section-id'));
  });
  return $.ajax({
    url: "/projects/" + project_id + "/sections/reorder",
    type: "POST",
    data: {
      sections: sections
    },
    success: function(data) {
      return remove_section_cover();
    }
  });
};

update_section_category_dropdown_from_input = function() {
  var option, val;
  val = $("#section-category-input").val();
  option = $("#section-category-select option[value='" + val + "']");
  if (option.length > 0) {
    option.attr('selected', true);
    return $("#section-category-input").hide();
  } else {
    $("#section-category-select option[value=Other]").attr('selected', true);
    return $("#section-category-input").show();
  }
};

section_category_dropdown_changed = function() {
  var val;
  val = $("#section-category-select").val();
  if (val !== "Other") {
    $("#section-category-input").hide();
    return $("#section-category-input").val(val);
  } else {
    $("#section-category-input").val('');
    return $("#section-category-input").show();
  }
};

search_available_sections = function() {
  var project_id, query;
  query = $("#available-sections-filter").val();
  project_id = $(".available-sections-table").data('project-id');
  $(".available-sections-table").addClass("loading");
  return $.ajax({
    url: "/projects/" + project_id + "/search-available-sections",
    type: "GET",
    data: {
      query: query
    },
    success: function(data) {
      var new_available_sections;
      new_available_sections = $(data.available_sections_tbody_html);
      $(".available-sections-table tbody.section").remove();
      $(".available-sections-table thead").after(new_available_sections);
      return $(".available-sections-table").removeClass("loading");
    }
  });
};

Rfpez.has_unsaved_changes = false;

$(document).on("ready page:load sectionsreloaded", function() {
  hide_already_selected_sections();
  $(".category-sections").sortable({
    forcePlaceholderSize: true
  });
  $(".sections-for-editing").sortable({
    handle: "h5",
    forcePlaceholderSize: true
  });
  $(".sections-for-editing").bind('sortupdate', save_sort_order);
  if ($(".fill-in-blanks")) {
    return add_empty_class_to_inputs();
  }
});

$(document).on("click", ".sow-sidebar a", function(e) {
  if (Rfpez.has_unsaved_changes === true && !confirm('Looks like you have some unsaved changes. Are you sure you want to leave this page?')) {
    return e.preventDefault();
  }
});

$(document).on("click", ".show-more-templates-link", function() {
  var li;
  li = $(this).closest("li");
  li.addClass("loading-more");
  return $.ajax({
    url: $(this).data('href'),
    type: "GET",
    success: function(data) {
      var new_templates;
      new_templates = $(data.html);
      li.before(new_templates);
      li.removeClass('loading-more');
      return li.addClass('all-loaded');
    }
  });
});

$(document).on("click", ".sections-for-editing .remove-button", function(e) {
  var el;
  e.preventDefault();
  el = $(this);
  el.button('loading');
  return $.ajax({
    url: el.data('href'),
    type: "DELETE",
    data: {
      requested_html: "sections_for_editing"
    },
    success: function(data) {
      var new_sections_for_editing;
      new_sections_for_editing = $(data.sections_for_editing_html);
      $(".sections-for-editing-wrapper").replaceWith(new_sections_for_editing);
      $(document).trigger("sectionsreloaded");
      return el.button('reset');
    }
  });
});

$(document).on("click", ".selected-sections .remove-button", function(e) {
  var el;
  e.preventDefault();
  el = $(this);
  el.button('loading');
  return $.ajax({
    url: el.data('href'),
    type: "DELETE",
    data: {
      requested_html: "selected_sections"
    },
    success: function(data) {
      var new_selected_sections;
      new_selected_sections = $(data.selected_sections_html);
      $(".selected-sections").replaceWith(new_selected_sections);
      return hide_already_selected_sections();
    }
  });
});

$(document).on("click", ".section .add-button", function(e) {
  var el;
  e.preventDefault();
  el = $(this);
  el.button('loading');
  return $.ajax({
    url: el.data('href'),
    type: "POST",
    success: function(data) {
      var new_sections_for_editing;
      new_sections_for_editing = $(data.sections_for_editing_html);
      $(".sections-for-editing-wrapper").replaceWith(new_sections_for_editing);
      $(document).trigger("sectionsreloaded");
      $("#add-edit-section-modal").modal('hide');
      hide_already_selected_sections();
      return el.button('reset');
    }
  });
});

$(document).on("click", ".add-section-button", function() {
  $("#edit-section-form").resetForm();
  $("#edit-section-form").find("input[name=section_id]").val('');
  $("#add-edit-section-modal").find(".modal-header h3").text("Add Section");
  $("#add-edit-section-modal").find(".will-fork").hide();
  $("#section-category-select").val("Deliverables");
  section_category_dropdown_changed();
  hide_already_selected_sections();
  return $("#add-edit-section-modal").modal('show');
});

$(document).on("click", ".edit-section-link", function() {
  var body, category, section, section_id, title;
  section = $(this).closest(".section");
  section_id = section.data('section-id');
  title = section.data('section-title');
  body = section.find(".body").html();
  category = section.closest(".category").data('name');
  if (section.data('will-fork') === true) {
    $("#add-edit-section-modal").find(".will-fork").show();
  } else {
    $("#add-edit-section-modal").find(".will-fork").hide();
  }
  $("#add-edit-section-modal").find(".modal-header h3").text("Edit Section '" + title + "'");
  $("#edit-section-form").find("input[name=section_id]").val(section_id);
  $("#edit-section-form").find("input[name=project_section\\[section_category\\]]").val(category);
  $("#edit-section-form").find("input[name=project_section\\[title\\]]").val(title);
  $("#edit-section-form").find("textarea[name=project_section\\[body\\]]").val(body);
  update_section_category_dropdown_from_input();
  $("#add-edit-section-modal .section-form-li a").click();
  return $("#add-edit-section-modal").modal('show');
});

$(document).on("submit", "#edit-section-form", function(e) {
  var button, el;
  e.preventDefault();
  el = $(this);
  button = el.find(".save-button");
  button.button('loading');
  return el.ajaxSubmit({
    success: function(data) {
      var new_sections_for_editing;
      new_sections_for_editing = $(data.sections_for_editing_html);
      $(".sections-for-editing-wrapper").replaceWith(new_sections_for_editing);
      $(document).trigger("sectionsreloaded");
      $("#add-edit-section-modal").modal('hide');
      return button.button('reset');
    }
  });
});

$(document).on("click", "li.template .preview-button", function() {
  return $(this).closest('div').find('.modal').modal('show');
});

$(document).on("change", "#section-category-select", section_category_dropdown_changed);

$(document).on("click", "tbody.section", function(e) {
  if (!$(e.target).hasClass('add-button')) {
    return $(this).find(".preview").toggle();
  }
});

available_sections_filter_timeout = false;

$(document).on("input", "#available-sections-filter", function() {
  clearTimeout(available_sections_filter_timeout);
  return available_sections_filter_timeout = setTimeout(function() {
    return search_available_sections();
  }, 200);
});

add_empty_class_to_inputs = function() {
  return $(".fill-in-blanks input[type=text]").each(function() {
    if (!$(this).val()) {
      return $(this).addClass('empty');
    } else {
      return $(this).removeClass('empty');
    }
  });
};

$("input[data-variable]").autoGrow({
  comfortZone: 5
});

$(document).on("focus", "input[data-variable]", function() {
  var el;
  el = $(this);
  el.tooltip({
    title: el.data('helper-text'),
    placement: 'bottom',
    trigger: 'manual'
  });
  return el.tooltip('show');
});

$(document).on("keydown", "input[data-variable]", function(e) {
  var index, input, inputs;
  if (e.keyCode === 13 || e.keyCode === 9) {
    inputs = $("input[data-variable]");
    index = inputs.index(this) + 1;
    while (index < inputs.length) {
      input = $(inputs[index]);
      if (input.val() === "") {
        e.preventDefault();
        return input.select();
      }
      index++;
    }
  }
});

$(document).on("blur", "input[data-variable]", function() {
  return $(this).tooltip('hide');
});

$(document).on("input blur", "input[data-variable]", function(e) {
  var el, variableName, variableValue;
  Rfpez.has_unsaved_changes = true;
  el = $(this);
  variableName = el.data('variable');
  variableValue = el.val();
  $("input[data-variable=" + variableName + "]").each(function() {
    $(this).val(variableValue);
    return $(this).trigger("input.autogrow");
  });
  return add_empty_class_to_inputs();
});

$(document).on("change", "#project-type-select", function() {
  if ($(this).val() === "Other") {
    return $("#new-project-type-input").removeClass('hide');
  } else {
    return $("#new-project-type-input").val('').addClass('hide');
  }
});

(function() {
  var App, AppView, Comment, CommentList, CommentView, Comments, NotificationView;
  Comment = Backbone.Model.extend({
    validate: function(attrs) {
      if (!attrs.body) {
        return true;
      }
    },
    defaults: function() {
      return {
        owner: false
      };
    },
    clear: function() {
      return this.destroy();
    }
  });
  CommentList = Backbone.Collection.extend({
    model: Comment
  });
  CommentView = Backbone.View.extend({
    tagName: "div",
    className: "well comment",
    template: _.template("<div class=\"body\">\n  <span class=\"author\">\n    <%- officer.name %>\n  </span>\n  <span class=\"timestamp\">\n    <span class=\"posted-at\">Posted <span class=\"timeago\" title=\"<%- formatted_created_at %>\"></span></span>\n  </span>\n  <a class=\"delete-comment only-user only-user-<%- officer.user_id %>\">Delete</a>\n\n  <p class=\"no-margin\"><%= _.escape(body).replace(new RegExp('\\r?\\n', 'g'), '<br />') %></p>\n</div>"),
    events: {
      "click a.delete-comment": "clear"
    },
    initialize: function() {
      this.model.bind("change", this.render, this);
      return this.model.bind("destroy", this.remove, this);
    },
    render: function() {
      this.$el.html(this.template(this.model.toJSON()));
      this.$el.find(".timeago").timeago();
      return this;
    },
    clear: function() {
      return this.model.clear();
    }
  });
  NotificationView = Backbone.View.extend({
    tagName: "div",
    className: "notification",
    template: _.template("<i class=\"<%- js_parsed.icon %>\"></i>\n<%= js_parsed.text %>\n<div class=\"date\"><span class=\"timeago\" title=\"<%- formatted_created_at %>\"></span></div>"),
    parse: function() {
      var icon, text;
      if (this.model.attributes.notification_type === "Dismissal") {
        text = " <a href=\"" + this.model.attributes.parsed.link + "\">" + this.model.attributes.payload.bid.vendor.company_name + "'s</a> bid was declined. ";
        icon = "icon-thumbs-down";
      } else if (this.model.attributes.notification_type === "Undismissal") {
        text = " <a href=\"" + this.model.attributes.parsed.link + "\">" + this.model.attributes.payload.bid.vendor.company_name + "'s</a> bid was un-declined. ";
        icon = "icon-repeat";
      } else if (this.model.attributes.notification_type === "BidSubmit") {
        text = " <a href=\"" + this.model.attributes.parsed.link + "\">" + this.model.attributes.payload.bid.vendor.company_name + "</a> submitted a bid. ";
        icon = "icon-list-alt";
      } else if (this.model.attributes.notification_type === "Award") {
        text = " The Contract was awarded to <a href=\"" + this.model.attributes.parsed.link + "\">" + this.model.attributes.payload.bid.vendor.company_name + "</a>. ";
        icon = "icon-thumbs-up";
      } else if (this.model.attributes.notification_type === "ProjectCollaboratorAdded") {
        text = " " + this.model.attributes.payload.officer.User.email + " was added as a collaborator. ";
        icon = "icon-user";
      }
      return {
        text: text != null ? text : this.model.attributes.notification_type,
        icon: icon != null ? icon : "icon-arrow-right"
      };
    },
    initialize: function() {
      this.model.bind("change", this.render, this);
      return this.model.bind("destroy", this.remove, this);
    },
    render: function() {
      this.$el.html(this.template(_.extend(this.model.toJSON(), {
        js_parsed: this.parse()
      })));
      return this;
    },
    clear: function() {
      return this.model.clear();
    }
  });
  AppView = Backbone.View.extend({
    initialize: function() {
      Comments.bind('add', this.addOne, this);
      Comments.bind('reset', this.reset, this);
      Comments.bind('all', this.render, this);
      this.bind('errorAdding', this.showError);
      return $("#add-comment-form").submit(this.addNew);
    },
    addNew: function(e) {
      var dateString;
      e.preventDefault();
      dateString = new Date().toISOString();
      Comments.create({
        officer: {
          name: $("#add-comment-form").data('officer-name'),
          user_id: $("#add-comment-form").data('officer-user-id')
        },
        body: $("#add-comment-form textarea").val(),
        formatted_created_at: dateString
      }, {
        error: function(obj, err) {
          return obj.clear();
        }
      });
      return $("#add-comment-form").resetForm();
    },
    showError: function(errors) {
      return alert(errors[0]);
    },
    reset: function() {
      $(".comments-list").html('');
      return this.addAll();
    },
    render: function() {},
    addOne: function(model) {
      var html, view;
      if (model.attributes.notification_type) {
        view = new NotificationView({
          model: model
        });
      } else {
        view = new CommentView({
          model: model
        });
      }
      html = view.render().el;
      return $(".comments-list").append(html);
    },
    addAll: function() {
      return Comments.each(this.addOne);
    }
  });
  App = false;
  Comments = false;
  return Rfpez.Backbone.Comments = function(project_id, initialModels) {
    var initialCollection;
    Comments = new CommentList;
    initialCollection = Comments;
    App = new AppView({
      collection: initialCollection
    });
    initialCollection.reset(initialModels);
    initialCollection.url = "/projects/" + project_id + "/comments";
    return App;
  };
})();

(function() {
  var App, AppView, Collaborator, CollaboratorList, CollaboratorView, Collaborators;
  Collaborator = Backbone.Model.extend({
    validate: function(attrs) {
      var errors;
      errors = [];
      if (!attrs.User.email) {
        return true;
      } else if (!attrs.User.email.match(/.gov$/i)) {
        errors.push("Sorry, .gov addresses only");
      } else if (!attrs.id && Collaborators.existing_emails().indexOf(attrs.User.email.toLowerCase()) !== -1) {
        errors.push("That collaborator already exists.");
      }
      if (errors.length > 0) {
        App.trigger('errorAdding', errors);
        return errors;
      }
    },
    defaults: function() {
      return {
        owner: false
      };
    },
    clear: function() {
      return this.destroy();
    }
  });
  CollaboratorList = Backbone.Collection.extend({
    existing_emails: function() {
      return this.map(function(c) {
        return c.attributes.User.email.toLowerCase();
      });
    },
    model: Collaborator
  });
  CollaboratorView = Backbone.View.extend({
    tagName: "tr",
    template: _.template("<td class=\"email\"><%- User.email %></td>\n<td>\n  <% if (pivot.owner === \"1\") { %>\n    <i class=\"icon-star\"></i>\n  <% } %>\n</td>\n<td>\n  <span class=\"not-user-<%- User.id %> only-user only-user-<%- owner_id %>\">\n    <% if (pivot.owner !== \"1\") { %>\n      <button class=\"btn btn-danger\">Remove</button>\n    <% } else { %>\n      Can't remove the owner.\n    <% } %>\n  </span>\n  <span class=\"only-user only-user-<%- User.id %>\">\n    That's you!\n  </span>\n</td>"),
    events: {
      "click .btn.btn-danger": "clear"
    },
    initialize: function() {
      this.model.bind("change", this.render, this);
      return this.model.bind("destroy", this.remove, this);
    },
    render: function() {
      this.$el.html(this.template(_.extend(this.model.toJSON(), {
        owner_id: App.options.owner_id
      })));
      return this;
    },
    clear: function() {
      return this.model.clear();
    }
  });
  AppView = Backbone.View.extend({
    initialize: function() {
      Collaborators.bind('add', this.addOne, this);
      Collaborators.bind('reset', this.reset, this);
      Collaborators.bind('all', this.render, this);
      this.bind('errorAdding', this.showError);
      return $("#add-collaborator-form").submit(this.addNew);
    },
    addNew: function(e) {
      var email;
      e.preventDefault();
      email = $("#add-collaborator-form input[name=email]").val();
      $("#add-collaborator-form input[name=email]").val('');
      return Collaborators.create({
        User: {
          email: email
        },
        pivot: {
          owner: 0
        }
      }, {
        error: function(obj, err) {
          return obj.clear();
        }
      });
    },
    showError: function(errors) {
      return $("#add-collaborator-form button").flash_button_message("warning", errors[0]);
    },
    reset: function() {
      $("#collaborators-tbody").html('');
      return this.addAll();
    },
    render: function() {},
    addOne: function(collaborator) {
      var html, view;
      view = new CollaboratorView({
        model: collaborator
      });
      html = view.render().el;
      return $("#collaborators-tbody").append(html);
    },
    addAll: function() {
      return Collaborators.each(this.addOne);
    }
  });
  App = false;
  Collaborators = false;
  return Rfpez.Backbone.Collaborators = function(project_id, owner_id, initialModels) {
    var initialCollection;
    Collaborators = new CollaboratorList;
    initialCollection = Collaborators;
    App = new AppView({
      collection: initialCollection,
      owner_id: owner_id
    });
    initialCollection.reset(initialModels);
    initialCollection.url = "/projects/" + project_id + "/collaborators";
    return App;
  };
})();

(function() {
  var App, AppView, Deliverable, DeliverableList, DeliverableView, Deliverables;
  Deliverable = Backbone.Model.extend({
    validate: function(attrs) {},
    defaults: function() {
      return {
        date: "",
        name: "",
        sort_order: $("#deliverables-tbody tr").length
      };
    },
    clear: function() {
      return this.destroy();
    }
  });
  DeliverableList = Backbone.Collection.extend({
    model: Deliverable
  });
  DeliverableView = Backbone.View.extend({
    tagName: "tr",
    template: _.template("<td>\n  <input type=\"text\" placeholder=\"Deliverable Name\" class=\"name-input\" value=\"<%- name %>\">\n</td>\n<td class=\"completion-date\">\n  <div class=\"input-append date datepicker-wrapper\">\n    <input type=\"text\" placeholder=\"Due Date\" class=\"date-input\" value=\"<%- date %>\" />\n    <span class=\"add-on\">\n      <i class=\"icon-calendar\"></i>\n    </span>\n  </div>\n</td>\n<td>\n  <a class=\"btn remove-deliverable-button\"><i class=\"icon-trash\"></i></a>\n</td>"),
    events: {
      "click .remove-deliverable-button": "clear",
      "input .name-input": "updateWithDelay",
      "input .date-input": "updateWithDelay",
      "change .date-input": "updateWithDelay"
    },
    initialize: function() {
      this.model.bind("change", this.updateId, this);
      this.model.bind("create", this.render, this);
      return this.model.bind("destroy", this.remove, this);
    },
    render: function() {
      var _ref, _ref1;
      this.$el.html(this.template(this.model.toJSON()));
      this.$el.find('.datepicker-wrapper').datepicker();
      this.$el.data('id', (_ref = this.model) != null ? (_ref1 = _ref.attributes) != null ? _ref1.id : void 0 : void 0);
      return this;
    },
    updateWithDelay: function() {
      var _this = this;
      Rfpez.has_unsaved_changes = true;
      if (this.updateTimeout) {
        clearTimeout(this.updateTimeout);
      }
      return this.updateTimeout = setTimeout(function() {
        return _this.update();
      }, 200);
    },
    update: function() {
      Rfpez.has_unsaved_changes = false;
      return this.model.save({
        name: this.$el.find(".name-input").val(),
        date: this.$el.find(".date-input").val(),
        sort_order: $("#deliverables-tbody tr").index(this.$el)
      });
    },
    updateId: function() {
      var _ref, _ref1;
      return this.$el.data('id', (_ref = this.model) != null ? (_ref1 = _ref.attributes) != null ? _ref1.id : void 0 : void 0);
    },
    clear: function() {
      return this.model.clear();
    }
  });
  AppView = Backbone.View.extend({
    initialize: function() {
      var _this = this;
      Deliverables.bind('add', this.addOne, this);
      Deliverables.bind('reset', this.reset, this);
      Deliverables.bind('all', this.render, this);
      $("#deliverables-tbody").bind('sortupdate', function() {
        var ordered_ids;
        ordered_ids = [];
        $("#deliverables-tbody tr").each(function() {
          return ordered_ids.push($(this).data('id'));
        });
        return $.ajax({
          url: "/projects/" + _this.options.project_id + "/deliverables/order",
          type: "PUT",
          data: {
            deliverable_ids: ordered_ids
          }
        });
      });
      return $(document).on("click", ".add-deliverable-timeline-button", function() {
        return _this.addNew();
      });
    },
    reset: function() {
      $("#deliverables-tbody").html('');
      return this.addAll();
    },
    addNew: function() {
      return Deliverables.create();
    },
    addOne: function(deliverable) {
      var html, view;
      view = new DeliverableView({
        model: deliverable
      });
      html = view.render().el;
      return $("#deliverables-tbody").append(html);
    },
    render: function() {
      $('#deliverables-tbody').sortable('destroy');
      return $("#deliverables-tbody").sortable({
        forcePlaceholderSize: true
      });
    },
    addAll: function() {
      return Deliverables.each(this.addOne);
    }
  });
  App = {};
  Deliverables = {};
  return Rfpez.Backbone.SowDeliverables = function(project_id, initialModels) {
    Deliverables = new DeliverableList;
    App = new AppView({
      collection: Deliverables,
      project_id: project_id
    });
    Deliverables.reset(initialModels);
    Deliverables.url = "/projects/" + project_id + "/deliverables";
    return App;
  };
})();

var dismiss_selection, keep_bid_in_view, mouseover_select_timeout, on_mouseover_select, open_selection, star_selection, toggle_unread_selection;

$(document).on('shown', '#dismiss-modal', function() {
  $(this).find("select").focus().val('');
  return $(this).find("input[name=reason_other]").val('').hide();
});

$(document).on("change", "#dismiss-modal select", function() {
  if ($(this).val() === "Other") {
    return $("#dismiss-modal input[name=reason_other]").show();
  } else {
    return $("#dismiss-modal input[name=reason_other]").val('').hide();
  }
});

$(document).on("click", "#review-tips-toggle", function() {
  return $("#review-tips").collapse('toggle');
});

$(document).on("show", "#review-tips", function() {
  $("#review-tips-toggle").data('show-text', $("#review-tips-toggle").text());
  return $("#review-tips-toggle").text($("#review-tips-toggle").data('hide-text'));
});

$(document).on("hide", "#review-tips", function() {
  return $("#review-tips-toggle").text($("#review-tips-toggle").data('show-text'));
});

$(document).on("click", ".bid-notification-td .mark-as-read, .bid-notification-td .mark-as-unread", function() {
  var action, bid, bid_id, el;
  el = $(this);
  bid = el.closest(".bid");
  bid_id = bid.data('bid-id');
  action = el.hasClass('mark-as-read') ? "read" : "unread";
  Rfpez.view_notification_payload("bid", bid_id, action);
  if (action === "read") {
    return bid.removeClass('unread');
  } else {
    return bid.addClass('unread');
  }
});

$(document).on("click", ".bid .unstar-button, .bid .star-button", function() {
  var action, bid;
  action = $(this).hasClass('unstar-button') ? "0" : "1";
  bid = $(this).closest(".bid");
  return $.ajax({
    url: "/projects/" + bid.data('project-id') + "/bids/" + bid.data('bid-id') + "/star",
    data: {
      starred: action
    },
    type: "GET",
    success: function(data) {
      if (data.starred === '0') {
        return bid.find(".star-td").removeClass("starred");
      } else {
        return bid.find(".star-td").addClass("starred");
      }
    }
  });
});

$(document).on('show', '.bid-details .collapse', function() {
  var bid, bid_id;
  bid = $(this).closest(".bid");
  bid_id = bid.data('bid-id');
  $(this).find(".dsbs-certifications").trigger('load-dsbs');
  bid.removeClass('unread');
  return Rfpez.view_notification_payload('bid', bid_id, "read");
});

$(document).on("click", ".undismiss-button", function() {
  var bid, bid_id, data_el, el, project_id;
  el = $(this);
  bid = el.closest(".bid");
  data_el = el.closest("[data-bid-id]");
  project_id = data_el.data('project-id');
  bid_id = data_el.data('bid-id');
  return $.ajax({
    url: "/projects/" + project_id + "/bids/" + bid_id + "/dismiss",
    type: "GET",
    success: function(data) {
      var new_bid;
      if (data.status === "success") {
        if (el.data('move-to-table')) {
          Rfpez.move_bid_selection("down");
          new_bid = $(data.html);
          bid.remove();
          return $(".bids-table.open-bids > thead").after(new_bid);
        } else {
          return window.location.reload();
        }
      }
    }
  });
});

$(document).on("click", ".show-dismiss-modal", function() {
  var bid, bid_id, data_el, el, modal, project_id, vendor_company_name;
  el = $(this);
  bid = el.closest(".bid");
  data_el = el.closest("[data-bid-id]");
  project_id = data_el.data('project-id');
  bid_id = data_el.data('bid-id');
  vendor_company_name = data_el.data('vendor-company-name');
  modal = $("#dismiss-modal");
  modal.find(".company-name").text(vendor_company_name);
  modal.find("textarea").val("");
  modal.find(".dismiss-btn").button('reset');
  modal.modal('show');
  modal.off(".rfpez-dismiss");
  return modal.on("submit.rfpez-dismiss", "form", function(e) {
    e.preventDefault();
    $(this).find(".dismiss-btn").button('loading');
    return $.ajax({
      url: "/projects/" + project_id + "/bids/" + bid_id + "/dismiss",
      data: {
        reason: modal.find("select[name=reason]").val(),
        reason_other: modal.find("input[name=reason_other]").val(),
        explanation: modal.find("textarea[name=explanation]").val()
      },
      type: "GET",
      dataType: "json",
      success: function(data) {
        var new_bid;
        if (data.status === "already dismissed" || "success") {
          modal.modal('hide');
          if (el.data('move-to-table')) {
            Rfpez.move_bid_selection("down");
            bid.remove();
            new_bid = $(data.html);
            return $(".bids-table.dismissed-bids > thead").after(new_bid);
          } else {
            return window.location.reload();
          }
        }
      }
    });
  });
});

$(document).on("click", ".show-award-modal", function() {
  var bid, bid_id, data_el, el, modal, project_id, vendor_company_name, vendor_email;
  el = $(this);
  bid = el.closest(".bid");
  data_el = el.closest("[data-bid-id]");
  project_id = data_el.data('project-id');
  bid_id = data_el.data('bid-id');
  vendor_company_name = data_el.data('vendor-company-name');
  vendor_email = data_el.data('vendor-email');
  modal = $("#award-modal");
  modal.find(".company-name").text(vendor_company_name);
  modal.find(".vendor-email").html("<a href=\"mailto:" + vendor_email + "\">" + vendor_email + "</a>");
  modal.find(".award-btn").button('reset');
  modal.modal('show');
  modal.off(".rfpez-award");
  return modal.on("submit.rfpez-award", "form", function(e) {
    e.preventDefault();
    $(this).find(".award-btn").button('loading');
    return $.ajax({
      url: "/projects/" + project_id + "/bids/" + bid_id + "/award",
      data: {
        awarded_message: modal.find("textarea[name=awarded_message]").val()
      },
      type: "GET",
      dataType: "json",
      success: function(data) {
        if (data.status === "success") {
          modal.modal('hide');
          return window.location.reload();
        }
      }
    });
  });
});

$(document).on("click", ".manual-awarded-message-checkbox", function() {
  var awarded_message, el, modal;
  el = $(this);
  modal = $("#award-modal");
  awarded_message = modal.find(".awarded-message");
  if (el.is(":checked")) {
    return awarded_message.data('original-val', awarded_message.val()).val("").attr('disabled', true);
  } else {
    return awarded_message.val(awarded_message.data('original-val')).removeAttr('disabled');
  }
});

on_mouseover_select = true;

mouseover_select_timeout = false;

keep_bid_in_view = function(bid, scrollTo) {
  var bottom, current_bottom, current_top, top;
  on_mouseover_select = false;
  clearTimeout(mouseover_select_timeout);
  if (scrollTo === "bid") {
    bottom = bid.offset().top + bid.height();
    current_bottom = $(window).scrollTop() + $(window).height();
    top = bid.offset().top;
    current_top = $(window).scrollTop();
    if (current_bottom < bottom) {
      $('html, body').scrollTop(bottom - $(window).height());
    }
    if (current_top > top) {
      $('html, body').scrollTop(bid.offset().top);
    }
  } else if (scrollTo === "top") {
    $('html, body').scrollTop(0);
  }
  return mouseover_select_timeout = setTimeout(function() {
    return on_mouseover_select = true;
  }, 200);
};

Rfpez.select_bid = function(bid, scrollTo) {
  $(".bid").removeClass('selected');
  bid.addClass('selected');
  if (scrollTo) {
    return keep_bid_in_view(bid, scrollTo);
  }
};

Rfpez.move_bid_selection = function(direction) {
  var all_bids, new_index, new_selection, selected_bid, selected_index;
  selected_bid = $(".bid.selected:eq(0)");
  if (!selected_bid) {
    return;
  }
  all_bids = $(".bid");
  selected_index = all_bids.index(selected_bid);
  if (direction === "up") {
    if (selected_index === 0) {
      return Rfpez.select_bid(selected_bid, "top");
    }
    new_index = selected_index - 1;
  } else {
    new_index = selected_index + 1;
  }
  new_selection = $(".bid:eq(" + new_index + ")");
  if (new_selection.length > 0) {
    return Rfpez.select_bid(new_selection, "bid");
  }
};

star_selection = function() {
  var selected_bid;
  selected_bid = $(".bid.selected:eq(0)");
  return selected_bid.find(".star-td .btn:visible").click();
};

open_selection = function() {
  var selected_bid;
  selected_bid = $(".bid.selected:eq(0)");
  return selected_bid.find("a[data-toggle=collapse]").click();
};

dismiss_selection = function() {
  var selected_bid;
  selected_bid = $(".bid.selected:eq(0)");
  return selected_bid.find(".show-dismiss-modal, .undismiss-button").filter(":visible").click();
};

toggle_unread_selection = function() {
  var selected_bid;
  selected_bid = $(".bid.selected:eq(0)");
  if (selected_bid.find(".mark-as-read").is(":visible")) {
    return selected_bid.find(".mark-as-read").click();
  } else {
    return selected_bid.find(".mark-as-unread").click();
  }
};

$(document).bind('keydown', 'k', function() {
  return Rfpez.move_bid_selection("up");
});

$(document).bind('keydown', 'j', function() {
  return Rfpez.move_bid_selection("down");
});

$(document).bind('keydown', 's', star_selection);

$(document).bind('keydown', 'return', open_selection);

$(document).bind('keydown', 'o', open_selection);

$(document).bind('keydown', 'd', dismiss_selection);

$(document).bind('keydown', 'u', toggle_unread_selection);

$(document).on("mouseover.selectbidmouseover", ".bid", function() {
  if (Rfpez.current_page("bid-review") && on_mouseover_select) {
    return Rfpez.select_bid($(this), false);
  }
});
