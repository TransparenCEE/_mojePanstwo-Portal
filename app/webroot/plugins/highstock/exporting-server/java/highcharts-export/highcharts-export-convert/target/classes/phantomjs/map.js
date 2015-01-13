/*
 Highmaps JS v1.0.0-beta-modified ()

 (c) 2011-2014 Torstein Honsi

 License: www.highcharts.com/license
 */
(function (i) {
    function F(a, b, d, c, e, f, g, h, j) {
        a = a["stroke-width"] % 2 / 2;
        b -= a;
        d -= a;
        return ["M", b + f, d, "L", b + c - g, d, "C", b + c - g / 2, d, b + c, d + g / 2, b + c, d + g, "L", b + c, d + e - h, "C", b + c, d + e - h / 2, b + c - h / 2, d + e, b + c - h, d + e, "L", b + j, d + e, "C", b + j / 2, d + e, b, d + e - j / 2, b, d + e - j, "L", b, d + f, "C", b, d + f / 2, b + f / 2, d, b + f, d, "Z"]
    }

    var o = i.Axis, A = i.Chart, x = i.Color, y = i.Point, u = i.Pointer, B = i.Legend, D = i.LegendSymbolMixin, J = i.Renderer, v = i.Series, E = i.SVGRenderer, G = i.VMLRenderer, H = i.addEvent, k = i.each, p = i.extend, t = i.extendClass, n = i.merge, m = i.pick, I = i.numberFormat,
        z = i.getOptions(), l = i.seriesTypes, q = z.plotOptions, r = i.wrap, s = function () {
        };
    r(o.prototype, "getSeriesExtremes", function (a) {
        var b = this.isXAxis, d, c, e = [], f;
        b && k(this.series, function (a, b) {
            if (a.useMapGeometry)e[b] = a.xData, a.xData = []
        });
        a.call(this);
        if (b && (d = m(this.dataMin, Number.MAX_VALUE), c = m(this.dataMax, Number.MIN_VALUE), k(this.series, function (a, b) {
                if (a.useMapGeometry)d = Math.min(d, m(a.minX, d)), c = Math.max(c, m(a.maxX, d)), a.xData = e[b], f = !0
            }), f))this.dataMin = d, this.dataMax = c
    });
    r(o.prototype, "setAxisTranslation",
        function (a) {
            var b = this.chart, d = b.plotWidth / b.plotHeight, c = b.xAxis[0];
            a.call(this);
            if (b.options.chart.preserveAspectRatio && this.coll === "yAxis" && c.transA !== void 0 && (this.transA = c.transA = Math.min(this.transA, c.transA), a = d / ((c.max - c.min) / (this.max - this.min)), c = a < 1 ? this : c, a = (c.max - c.min) * c.transA, c.pixelPadding = c.len - a, c.minPixelPadding = c.pixelPadding / 2, a = c.fixTo)) {
                a = a[1] - c.toValue(a[0], !0);
                a *= c.transA;
                if (Math.abs(a) > c.minPixelPadding || c.min === c.dataMin && c.max === c.dataMax)a = 0;
                c.minPixelPadding -= a
            }
        });
    r(o.prototype,
        "render", function (a) {
            a.call(this);
            this.fixTo = null
        });
    var C = i.ColorAxis = function () {
        this.isColorAxis = !0;
        this.init.apply(this, arguments)
    };
    p(C.prototype, o.prototype);
    p(C.prototype, {
        defaultColorAxisOptions: {
            lineWidth: 0,
            gridLineWidth: 1,
            tickPixelInterval: 72,
            startOnTick: !0,
            endOnTick: !0,
            offset: 0,
            marker: {animation: {duration: 50}, color: "gray", width: 0.01},
            labels: {overflow: "justify"},
            minColor: "#EFEFFF",
            maxColor: "#003875",
            tickLength: 5
        }, init: function (a, b) {
            var d = a.options.legend.layout !== "vertical", c;
            c = n(this.defaultColorAxisOptions,
                {side: d ? 2 : 1, reversed: !d}, b, {isX: d, opposite: !d, showEmpty: !1, title: null, isColor: !0});
            o.prototype.init.call(this, a, c);
            b.dataClasses && this.initDataClasses(b);
            this.initStops(b);
            this.isXAxis = !0;
            this.horiz = d;
            this.zoomEnabled = !1
        }, tweenColors: function (a, b, d) {
            var c = b.rgba[3] !== 1 || a.rgba[3] !== 1;
            return (c ? "rgba(" : "rgb(") + Math.round(b.rgba[0] + (a.rgba[0] - b.rgba[0]) * (1 - d)) + "," + Math.round(b.rgba[1] + (a.rgba[1] - b.rgba[1]) * (1 - d)) + "," + Math.round(b.rgba[2] + (a.rgba[2] - b.rgba[2]) * (1 - d)) + (c ? "," + (b.rgba[3] + (a.rgba[3] - b.rgba[3]) *
                (1 - d)) : "") + ")"
        }, initDataClasses: function (a) {
            var b = this, d = this.chart, c, e = 0, f = this.options;
            this.dataClasses = c = [];
            k(a.dataClasses, function (g, h) {
                var j, g = n(g);
                c.push(g);
                if (!g.color)f.dataClassColor === "category" ? (j = d.options.colors, g.color = j[e++], e === j.length && (e = 0)) : g.color = b.tweenColors(x(f.minColor), x(f.maxColor), h / (a.dataClasses.length - 1))
            })
        }, initStops: function (a) {
            this.stops = a.stops || [[0, this.options.minColor], [1, this.options.maxColor]];
            k(this.stops, function (a) {
                a.color = x(a[1])
            })
        }, setOptions: function (a) {
            o.prototype.setOptions.call(this,
                a);
            this.options.crosshair = this.options.marker;
            this.coll = "colorAxis"
        }, setAxisSize: function () {
            var a = this.legendSymbol, b = this.chart, d, c, e;
            if (a)this.left = d = a.attr("x"), this.top = c = a.attr("y"), this.width = e = a.attr("width"), this.height = a = a.attr("height"), this.right = b.chartWidth - d - e, this.bottom = b.chartHeight - c - a, this.len = this.horiz ? e : a, this.pos = this.horiz ? d : c
        }, toColor: function (a, b) {
            var d, c = this.stops, e, f = this.dataClasses, g, h;
            if (f)for (h = f.length; h--;) {
                if (g = f[h], e = g.from, c = g.to, (e === void 0 || a >= e) && (c === void 0 ||
                    a <= c)) {
                    d = g.color;
                    if (b)b.dataClass = h;
                    break
                }
            } else {
                this.isLog && (a = this.val2lin(a));
                d = 1 - (this.max - a) / (this.max - this.min);
                for (h = c.length; h--;)if (d > c[h][0])break;
                e = c[h] || c[h + 1];
                c = c[h + 1] || e;
                d = 1 - (c[0] - d) / (c[0] - e[0] || 1);
                d = this.tweenColors(e.color, c.color, d)
            }
            return d
        }, getOffset: function () {
            var a = this.legendGroup;
            if (a && (o.prototype.getOffset.call(this), !this.axisGroup.parentGroup))this.axisGroup.add(a), this.gridGroup.add(a), this.labelGroup.add(a), this.added = !0
        }, setLegendColor: function () {
            var a, b = this.options;
            a = this.horiz ? [0, 0, 1, 0] : [0, 0, 0, 1];
            this.legendColor = {
                linearGradient: {x1: a[0], y1: a[1], x2: a[2], y2: a[3]},
                stops: b.stops || [[0, b.minColor], [1, b.maxColor]]
            }
        }, drawLegendSymbol: function (a, b) {
            var d = a.padding, c = a.options, e = this.horiz, f = m(c.symbolWidth, e ? 200 : 12), g = m(c.symbolHeight, e ? 12 : 200), c = m(c.labelPadding, e ? 10 : 30);
            this.setLegendColor();
            b.legendSymbol = this.chart.renderer.rect(0, a.baseline - 11, f, g).attr({zIndex: 1}).add(b.legendGroup);
            b.legendSymbol.getBBox();
            this.legendItemWidth = f + d + (e ? 0 : c);
            this.legendItemHeight =
                g + d + (e ? c : 0)
        }, setState: s, visible: !0, setVisible: s, getSeriesExtremes: function () {
            var a;
            if (this.series.length)a = this.series[0], this.dataMin = a.valueMin, this.dataMax = a.valueMax
        }, drawCrosshair: function (a, b) {
            var d = !this.cross, c = b && b.plotX, e = b && b.plotY, f, g = this.pos, h = this.len;
            if (b)f = this.toPixels(b.value), f < g ? f = g - 2 : f > g + h && (f = g + h + 2), b.plotX = f, b.plotY = this.len - f, o.prototype.drawCrosshair.call(this, a, b), b.plotX = c, b.plotY = e, !d && this.cross && this.cross.attr({fill: this.crosshair.color}).add(this.labelGroup)
        }, getPlotLinePath: function (a,
                                      b, d, c, e) {
            return e ? this.horiz ? ["M", e - 4, this.top - 6, "L", e + 4, this.top - 6, e, this.top, "Z"] : ["M", this.left, e, "L", this.left - 6, e + 6, this.left - 6, e - 6, "Z"] : o.prototype.getPlotLinePath.call(this, a, b, d, c)
        }, update: function (a, b) {
            k(this.series, function (a) {
                a.isDirtyData = !0
            });
            o.prototype.update.call(this, a, b);
            this.legendItem && (this.setLegendColor(), this.chart.legend.colorizeItem(this, !0))
        }, getDataClassLegendSymbols: function () {
            var a = this, b = this.chart, d = [], c = b.options.legend, e = c.valueDecimals, f = c.valueSuffix || "", g;
            k(this.dataClasses,
                function (c, j) {
                    var i = !0, w = c.from, l = c.to;
                    g = "";
                    w === void 0 ? g = "< " : l === void 0 && (g = "> ");
                    w !== void 0 && (g += I(w, e) + f);
                    w !== void 0 && l !== void 0 && (g += " - ");
                    l !== void 0 && (g += I(l, e) + f);
                    d.push(p({
                        chart: b,
                        name: g,
                        options: {},
                        drawLegendSymbol: D.drawRectangle,
                        visible: !0,
                        setState: s,
                        setVisible: function () {
                            i = this.visible = !i;
                            k(a.series, function (a) {
                                k(a.points, function (a) {
                                    a.dataClass === j && a.setVisible(i)
                                })
                            });
                            b.legend.colorizeItem(this, i)
                        }
                    }, c))
                });
            return d
        }, name: ""
    });
    r(A.prototype, "getAxes", function (a) {
        var b = this.options.colorAxis;
        a.call(this);
        this.colorAxis = [];
        b && new C(this, b)
    });
    r(B.prototype, "getAllItems", function (a) {
        var b = [], d = this.chart.colorAxis[0];
        d && (d.options.dataClasses ? b = b.concat(d.getDataClassLegendSymbols()) : b.push(d), k(d.series, function (a) {
            a.options.showInLegend = !1
        }));
        return b.concat(a.call(this))
    });
    B = {
        pointAttrToOptions: {
            stroke: "borderColor",
            "stroke-width": "borderWidth",
            fill: "color",
            dashstyle: "dashStyle"
        },
        pointArrayMap: ["value"],
        axisTypes: ["xAxis", "yAxis", "colorAxis"],
        optionalAxis: "colorAxis",
        trackerGroups: ["group",
            "markerGroup", "dataLabelsGroup"],
        getSymbol: s,
        parallelArrays: ["x", "y", "value"],
        translateColors: function () {
            var a = this, b = this.options.nullColor, d = this.colorAxis;
            k(this.data, function (c) {
                var e = c.value;
                if (e = e === null ? b : d ? d.toColor(e, c) : c.color || a.color)c.color = e
            })
        }
    };
    p(A.prototype, {
        renderMapNavigation: function () {
            var a = this, b = this.options.mapNavigation, d = b.buttons, c, e, f, g, h = function () {
                this.handler.call(a)
            };
            if (m(b.enableButtons, b.enabled) && !a.renderer.forExport)for (c in d)if (d.hasOwnProperty(c))f = n(b.buttonOptions,
                d[c]), e = f.theme, g = e.states, e = a.renderer.button(f.text, 0, 0, h, e, g && g.hover, g && g.select, 0, c === "zoomIn" ? "topbutton" : "bottombutton").attr({
                width: f.width,
                height: f.height,
                title: a.options.lang[c],
                zIndex: 5
            }).css(f.style).add(), e.handler = f.onclick, e.align(p(f, {
                width: e.width,
                height: 2 * e.height
            }), null, f.alignTo)
        }, fitToBox: function (a, b) {
            k([["x", "width"], ["y", "height"]], function (d) {
                var c = d[0], d = d[1];
                a[c] + a[d] > b[c] + b[d] && (a[d] > b[d] ? (a[d] = b[d], a[c] = b[c]) : a[c] = b[c] + b[d] - a[d]);
                a[d] > b[d] && (a[d] = b[d]);
                a[c] < b[c] && (a[c] =
                    b[c])
            });
            return a
        }, mapZoom: function (a, b, d, c, e) {
            var f = this.xAxis[0], g = f.max - f.min, h = m(b, f.min + g / 2), j = g * a, g = this.yAxis[0], i = g.max - g.min, w = m(d, g.min + i / 2);
            i *= a;
            h = this.fitToBox({
                x: h - j * (c ? (c - f.pos) / f.len : 0.5),
                y: w - i * (e ? (e - g.pos) / g.len : 0.5),
                width: j,
                height: i
            }, {x: f.dataMin, y: g.dataMin, width: f.dataMax - f.dataMin, height: g.dataMax - g.dataMin});
            if (c)f.fixTo = [c - f.pos, b];
            if (e)g.fixTo = [e - g.pos, d];
            a !== void 0 ? (f.setExtremes(h.x, h.x + h.width, !1), g.setExtremes(h.y, h.y + h.height, !1)) : (f.setExtremes(void 0, void 0, !1), g.setExtremes(void 0,
                void 0, !1));
            this.redraw()
        }
    });
    r(A.prototype, "render", function (a) {
        var b = this, d = b.options.mapNavigation;
        b.renderMapNavigation();
        a.call(b);
        (m(d.enableDoubleClickZoom, d.enabled) || d.enableDoubleClickZoomTo) && H(b.container, "dblclick", function (a) {
            b.pointer.onContainerDblClick(a)
        });
        m(d.enableMouseWheelZoom, d.enabled) && H(b.container, document.onmousewheel === void 0 ? "DOMMouseScroll" : "mousewheel", function (a) {
            b.pointer.onContainerMouseWheel(a);
            return !1
        })
    });
    p(u.prototype, {
        onContainerDblClick: function (a) {
            var b = this.chart,
                a = this.normalize(a);
            b.options.mapNavigation.enableDoubleClickZoomTo ? b.pointer.inClass(a.target, "highcharts-tracker") && b.hoverPoint.zoomTo() : b.isInsidePlot(a.chartX - b.plotLeft, a.chartY - b.plotTop) && b.mapZoom(0.5, b.xAxis[0].toValue(a.chartX), b.yAxis[0].toValue(a.chartY), a.chartX, a.chartY)
        }, onContainerMouseWheel: function (a) {
            var b = this.chart, d, a = this.normalize(a);
            d = a.detail || -(a.wheelDelta / 120);
            b.isInsidePlot(a.chartX - b.plotLeft, a.chartY - b.plotTop) && b.mapZoom(Math.pow(2, d), b.xAxis[0].toValue(a.chartX),
                b.yAxis[0].toValue(a.chartY), a.chartX, a.chartY)
        }
    });
    r(u.prototype, "init", function (a, b, d) {
        a.call(this, b, d);
        if (m(d.mapNavigation.enableTouchZoom, d.mapNavigation.enabled))this.pinchX = this.pinchHor = this.pinchY = this.pinchVert = !0
    });
    r(u.prototype, "pinchTranslate", function (a, b, d, c, e, f, g) {
        a.call(this, b, d, c, e, f, g);
        this.chart.options.chart.type === "map" && (a = c.scaleX > c.scaleY, this.pinchTranslateDirection(!a, b, d, c, e, f, g, a ? c.scaleX : c.scaleY))
    });
    q.map = n(q.scatter, {
        allAreas: !0,
        animation: !1,
        nullColor: "#F8F8F8",
        borderColor: "silver",
        borderWidth: 1,
        marker: null,
        stickyTracking: !1,
        dataLabels: {
            format: "{point.value}",
            verticalAlign: "middle",
            crop: !1,
            overflow: !1,
            style: {color: "white", fontWeight: "bold", textShadow: "0 0 5px black"}
        },
        turboThreshold: 0,
        tooltip: {followPointer: !0, pointFormat: "{point.name}: {point.value}<br/>"},
        states: {normal: {animation: !0}, hover: {brightness: 0.2}}
    });
    u = t(y, {
        applyOptions: function (a, b) {
            var d = y.prototype.applyOptions.call(this, a, b), c = this.series, e = c.options, f = c.joinBy;
            if (e.mapData)if (e = f[0] ? c.getMapData(f[0], d[f[1]]) :
                    e.mapData[d.x]) {
                if (c.xyFromShape)d.x = e._midX, d.y = e._midY;
                p(d, e)
            } else d.value = d.value || null;
            return d
        }, setVisible: function (a) {
            var b = this, d = a ? "show" : "hide";
            k(["graphic", "dataLabel"], function (a) {
                if (b[a])b[a][d]()
            })
        }, onMouseOver: function (a) {
            clearTimeout(this.colorInterval);
            y.prototype.onMouseOver.call(this, a)
        }, onMouseOut: function () {
            var a = this, b = +new Date, d = x(a.color), c = x(a.pointAttr.hover.fill), e = a.series.options.states.normal.animation, f = e && (e.duration || 500), g;
            if (f && d.rgba.length === 4 && c.rgba.length ===
                4 && a.state !== "select")g = a.pointAttr[""].fill, delete a.pointAttr[""].fill, clearTimeout(a.colorInterval), a.colorInterval = setInterval(function () {
                var e = (new Date - b) / f, g = a.graphic;
                e > 1 && (e = 1);
                g && g.attr("fill", C.prototype.tweenColors.call(0, c, d, e));
                e >= 1 && clearTimeout(a.colorInterval)
            }, 13);
            y.prototype.onMouseOut.call(a);
            if (g)a.pointAttr[""].fill = g
        }, zoomTo: function () {
            var a = this.series;
            a.xAxis.setExtremes(this._minX, this._maxX, !1);
            a.yAxis.setExtremes(this._minY, this._maxY, !1);
            a.chart.redraw()
        }
    });
    l.map = t(l.scatter,
        n(B, {
            type: "map",
            pointClass: u,
            supportsDrilldown: !0,
            getExtremesFromAll: !0,
            useMapGeometry: !0,
            forceDL: !0,
            getBox: function (a) {
                var b = Number.MAX_VALUE, d = -b, c = b, e = -b, f = b, g = b, h = this.xAxis, j = this.yAxis, l;
                k(a || [], function (a) {
                    if (a.path) {
                        if (typeof a.path === "string")a.path = i.splitPath(a.path);
                        var h = a.path || [], j = h.length, k = !1, m = -b, n = b, o = -b, p = b;
                        if (!a._foundBox) {
                            for (; j--;)typeof h[j] === "number" && !isNaN(h[j]) && (k ? (m = Math.max(m, h[j]), n = Math.min(n, h[j])) : (o = Math.max(o, h[j]), p = Math.min(p, h[j])), k = !k);
                            a._midX = n + (m - n) * (a.middleX ||
                            0.5);
                            a._midY = p + (o - p) * (a.middleY || 0.5);
                            a._maxX = m;
                            a._minX = n;
                            a._maxY = o;
                            a._minY = p;
                            a._foundBox = !0
                        }
                        d = Math.max(d, a._maxX);
                        c = Math.min(c, a._minX);
                        e = Math.max(e, a._maxY);
                        f = Math.min(f, a._minY);
                        g = Math.min(a._maxX - a._minX, a._maxY - a._minY, g);
                        l = !0
                    }
                });
                if (l) {
                    this.minY = Math.min(f, m(this.minY, b));
                    this.maxY = Math.max(e, m(this.maxY, -b));
                    this.minX = Math.min(c, m(this.minX, b));
                    this.maxX = Math.max(d, m(this.maxX, -b));
                    if (h.options.minRange === void 0)h.minRange = Math.min(5 * g, (this.maxX - this.minX) / 5, h.minRange || b);
                    if (j.options.minRange === void 0)j.minRange = Math.min(5 * g, (this.maxY - this.minY) / 5, j.minRange || b)
                }
            },
            getExtremes: function () {
                v.prototype.getExtremes.call(this, this.valueData);
                this.chart.hasRendered && this.isDirtyData && this.getBox(this.options.data);
                this.valueMin = this.dataMin;
                this.valueMax = this.dataMax;
                this.dataMin = this.minY;
                this.dataMax = this.maxY
            },
            translatePath: function (a) {
                var b = !1, d = this.xAxis, c = this.yAxis, e = d.min, f = d.transA, d = d.minPixelPadding, g = c.min, h = c.transA, c = c.minPixelPadding, j, i = [];
                if (a)for (j = a.length; j--;)typeof a[j] ===
                "number" ? (i[j] = b ? (a[j] - e) * f + d : (a[j] - g) * h + c, b = !b) : i[j] = a[j];
                return i
            },
            setData: function (a, b) {
                var d = this.options, c = d.mapData, e, f = [];
                e = this.joinBy = i.splat(d.joinBy);
                e[1] || (e[1] = e[0]);
                this.getBox(a);
                this.getBox(c);
                d.allAreas && c && (a = a || [], e[1] && k(a, function (a) {
                    f.push(a[e[1]])
                }), f = "|" + f.join("|") + "|", k(c, function (b) {
                    (!e[0] || f.indexOf("|" + (b[e[0]] || b.properties && b.properties[e[0]]) + "|") === -1) && a.push(n(b, {value: null}))
                }));
                v.prototype.setData.call(this, a, b)
            },
            getMapData: function (a, b) {
                var d = this.options.mapData,
                    c = this.mapMap, e, f = d.length;
                if (!c)c = this.mapMap = {};
                if (c[b] !== void 0)return d[c[b]]; else if (b !== void 0)for (; f--;)if (e = d[f], e[a] === b || e.properties && e.properties[a] === b)return c[b] = f, e
            },
            drawGraph: s,
            drawDataLabels: s,
            translate: function () {
                var a = this, b = a.xAxis, d = a.yAxis;
                a.generatePoints();
                k(a.data, function (c) {
                    c.plotX = b.toPixels(c._midX, !0);
                    c.plotY = d.toPixels(c._midY, !0);
                    if (a.isDirtyData || a.chart.renderer.isVML)c.shapeType = "path", c.shapeArgs = {
                        d: a.translatePath(c.path),
                        "vector-effect": "non-scaling-stroke"
                    }
                });
                a.translateColors()
            },
            drawPoints: function () {
                var a = this.xAxis, b = this.yAxis, d = this.group, c = this.chart, e = c.renderer, f = this.baseTrans;
                if (!this.transformGroup)this.transformGroup = e.g().attr({scaleX: 1, scaleY: 1}).add(d);
                this.isDirtyData || e.isVML || !f ? (c.hasRendered && this.pointAttrToOptions.fill === "color" && k(this.points, function (a) {
                    a.graphic && a.graphic.attr("fill", a.color)
                }), this.group = this.transformGroup, l.column.prototype.drawPoints.apply(this), this.group = d, this.baseTrans = {
                    originX: a.min - a.minPixelPadding /
                    a.transA,
                    originY: b.min - b.minPixelPadding / b.transA + (b.reversed ? 0 : b.len / b.transA),
                    transAX: a.transA,
                    transAY: b.transA
                }) : (d = a.transA / f.transAX, e = b.transA / f.transAY, d > 0.99 && d < 1.01 && e > 0.99 && e < 1.01 ? (b = a = 0, e = d = 1) : (a = a.toPixels(f.originX, !0), b = b.toPixels(f.originY, !0)), this.transformGroup.animate({
                    translateX: a,
                    translateY: b,
                    scaleX: d,
                    scaleY: e
                }));
                v.prototype.drawDataLabels.call(this);
                this.dataLabelsGroup && this.dataLabelsGroup.clip(c.clipRect)
            },
            render: function () {
                var a = this, b = v.prototype.render;
                a.chart.renderer.isVML &&
                a.data.length > 3E3 ? setTimeout(function () {
                    b.call(a)
                }) : b.call(a)
            },
            animate: function (a) {
                var b = this.options.animation, d = this.group, c = this.xAxis, e = this.yAxis, f = c.pos, g = e.pos;
                if (this.chart.renderer.isSVG)b === !0 && (b = {duration: 1E3}), a ? d.attr({
                    translateX: f + c.len / 2,
                    translateY: g + e.len / 2,
                    scaleX: 0.001,
                    scaleY: 0.001
                }) : (d.animate({translateX: f, translateY: g, scaleX: 1, scaleY: 1}, b), this.animate = null)
            },
            animateDrilldown: function (a) {
                var b = this.chart.plotBox, d = this.chart.drilldownLevels[this.chart.drilldownLevels.length - 1],
                    c = d.bBox, e = this.chart.options.drilldown.animation;
                if (!a)a = Math.min(c.width / b.width, c.height / b.height), d.shapeArgs = {
                    scaleX: a,
                    scaleY: a,
                    translateX: c.x,
                    translateY: c.y
                }, k(this.points, function (a) {
                    a.graphic.attr(d.shapeArgs).animate({scaleX: 1, scaleY: 1, translateX: 0, translateY: 0}, e)
                }), this.animate = null
            },
            drawLegendSymbol: D.drawRectangle,
            animateDrillupFrom: function (a) {
                l.column.prototype.animateDrillupFrom.call(this, a)
            },
            animateDrillupTo: function (a) {
                l.column.prototype.animateDrillupTo.call(this, a)
            }
        }));
    q.mapline =
        n(q.map, {lineWidth: 1, fillColor: "none"});
    l.mapline = t(l.map, {
        type: "mapline",
        pointAttrToOptions: {stroke: "color", "stroke-width": "lineWidth", fill: "fillColor", dashstyle: "dashStyle"},
        drawLegendSymbol: l.line.prototype.drawLegendSymbol
    });
    q.mappoint = n(q.scatter, {
        dataLabels: {
            enabled: !0,
            format: "{point.name}",
            color: "black",
            crop: !1,
            overflow: !1,
            style: {textShadow: "0 0 5px white"}
        }
    });
    l.mappoint = t(l.scatter, {type: "mappoint", forceDL: !0});
    if (l.bubble)q.mapbubble = n(q.bubble, {animationLimit: 500, tooltip: {pointFormat: "{point.name}: {point.z}"}}),
        l.mapbubble = t(l.bubble, {
            pointClass: t(y, {applyOptions: u.prototype.applyOptions}),
            xyFromShape: !0,
            type: "mapbubble",
            pointArrayMap: ["z"],
            getMapData: l.map.prototype.getMapData,
            getBox: l.map.prototype.getBox,
            setData: l.map.prototype.setData
        });
    z.plotOptions.heatmap = n(z.plotOptions.scatter, {
        animation: !1,
        borderWidth: 0,
        nullColor: "#F8F8F8",
        dataLabels: {
            format: "{point.value}",
            verticalAlign: "middle",
            crop: !1,
            overflow: !1,
            style: {color: "white", fontWeight: "bold", textShadow: "0 0 5px black"}
        },
        marker: null,
        tooltip: {pointFormat: "{point.x}, {point.y}: {point.value}<br/>"},
        states: {normal: {animation: !0}, hover: {brightness: 0.2}}
    });
    l.heatmap = t(l.scatter, n(B, {
        type: "heatmap",
        pointArrayMap: ["y", "value"],
        hasPointSpecificOptions: !0,
        supportsDrilldown: !0,
        getExtremesFromAll: !0,
        init: function () {
            l.scatter.prototype.init.apply(this, arguments);
            this.pointRange = this.options.colsize || 1;
            this.yAxis.axisPointRange = this.options.rowsize || 1
        },
        translate: function () {
            var a = this.options, b = this.xAxis, d = this.yAxis;
            this.generatePoints();
            k(this.points, function (c) {
                var e = (a.colsize || 1) / 2, f = (a.rowsize ||
                    1) / 2, g = Math.round(b.len - b.translate(c.x - e, 0, 1, 0, 1)), e = Math.round(b.len - b.translate(c.x + e, 0, 1, 0, 1)), h = Math.round(d.translate(c.y - f, 0, 1, 0, 1)), f = Math.round(d.translate(c.y + f, 0, 1, 0, 1));
                c.plotX = (g + e) / 2;
                c.plotY = (h + f) / 2;
                c.shapeType = "rect";
                c.shapeArgs = {x: Math.min(g, e), y: Math.min(h, f), width: Math.abs(e - g), height: Math.abs(f - h)}
            });
            this.translateColors()
        },
        drawPoints: l.column.prototype.drawPoints,
        animate: s,
        getBox: s,
        drawLegendSymbol: D.drawRectangle,
        getExtremes: function () {
            v.prototype.getExtremes.call(this, this.valueData);
            this.valueMin = this.dataMin;
            this.valueMax = this.dataMax;
            v.prototype.getExtremes.call(this)
        }
    }));
    i.geojson = function (a, b) {
        var d = [], c = [], e = function (a) {
            var b = 0, d = a.length;
            for (c.push("M"); b < d; b++)b === 1 && c.push("L"), c.push(a[b][0], -a[b][1])
        };
        k(a.features, function (a) {
            var g = a.geometry, h = g.type, g = g.coordinates, a = a.properties, i;
            c = [];
            b === "map" ? (h === "Polygon" ? (k(g, e), c.push("Z")) : h === "MultiPolygon" && (k(g, function (a) {
                k(a, e)
            }), c.push("Z")), c.length && (i = {path: c})) : b === "mapline" ? (h === "LineString" ? e(g) : h === "MultiLineString" &&
            k(g, e), c.length && (i = {path: c})) : b === "mappoint" && h === "Point" && (i = {x: g[0], y: -g[1]});
            i && d.push(p(i, {name: a.name || a.NAME, properties: a}))
        });
        return d
    };
    p(z.lang, {zoomIn: "Zoom in", zoomOut: "Zoom out"});
    z.mapNavigation = {
        buttonOptions: {
            alignTo: "plotBox",
            align: "left",
            verticalAlign: "top",
            x: 0,
            width: 18,
            height: 18,
            style: {fontSize: "15px", fontWeight: "bold", textAlign: "center"},
            theme: {"stroke-width": 1}
        }, buttons: {
            zoomIn: {
                onclick: function () {
                    this.mapZoom(0.5)
                }, text: "+", y: 0
            }, zoomOut: {
                onclick: function () {
                    this.mapZoom(2)
                }, text: "-",
                y: 28
            }
        }
    };
    i.splitPath = function (a) {
        var b, a = a.replace(/([A-Za-z])/g, " $1 "), a = a.replace(/^\s*/, "").replace(/\s*$/, ""), a = a.split(/[ ,]+/);
        for (b = 0; b < a.length; b++)/[a-zA-Z]/.test(a[b]) || (a[b] = parseFloat(a[b]));
        return a
    };
    i.maps = {};
    E.prototype.symbols.topbutton = function (a, b, d, c, e) {
        return F(e, a, b, d, c, e.r, e.r, 0, 0)
    };
    E.prototype.symbols.bottombutton = function (a, b, d, c, e) {
        return F(e, a, b, d, c, 0, 0, e.r, e.r)
    };
    J === G && k(["topbutton", "bottombutton"], function (a) {
        G.prototype.symbols[a] = E.prototype.symbols[a]
    });
    i.Map = function (a,
                      b) {
        var d = {
            endOnTick: !1,
            gridLineWidth: 0,
            lineWidth: 0,
            minPadding: 0,
            maxPadding: 0,
            startOnTick: !1,
            title: null,
            tickPositions: []
        }, c;
        c = a.series;
        a.series = null;
        a = n({chart: {panning: "xy", type: "map"}, xAxis: d, yAxis: n(d, {reversed: !0})}, a, {
            chart: {
                inverted: !1,
                alignTicks: !1,
                preserveAspectRatio: !0
            }
        });
        a.series = c;
        return new A(a, b)
    }
})(Highcharts);
