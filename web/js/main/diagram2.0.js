! function() {
    var containerSvgWidth = $('.panel-body').innerWidth();

    containerSvgWidth = containerSvgWidth >= 840 ? 840 : containerSvgWidth - 80;

    var width = containerSvgWidth,
        height = width,
        radius = width / 2,
        x = d3.scale.linear().range([0, 2 * Math.PI]),
        y = d3.scale.pow().exponent(1.3).domain([0, 1]).range([0, radius]),
        padding = 5,
        duration = 1000,
        path = '',
        text = '',
        textEnter = '',
        username = '';
    var div = d3.select("#sunburst");
    div.select("img").remove();
    var vis = div.append("svg")
        .attr("width", width + padding * 2)
        .attr("height", height + padding * 2)
        .append("g")
        .attr("transform", "translate(" + [radius + padding, radius + padding] + ")");
    var partition = d3.layout.partition()
        .sort(null)
        .value(function(d) { return 5.8 - d.depth; });
    var arc = d3.svg.arc()
        .startAngle(function(d) { return Math.max(0, Math.min(2 * Math.PI, x(d.x))); })
        .endAngle(function(d) { return Math.max(0, Math.min(2 * Math.PI, x(d.x + d.dx))); })
        .innerRadius(function(d) { return Math.max(0, d.y ? y(d.y) : d.y); })
        .outerRadius(function(d) { return Math.max(0, y(d.y + d.dy)); });

    d3.json(location.protocol + '//' + location.hostname + "/business/team/take-json?id=" + $('.user_id').attr('data-id'), function(error, json) {
        var nodes = partition.nodes({children: json});
        path = vis.selectAll("path").data(nodes);
        path.enter().append("path")
            .attr("id", function(d, i) { return "path-" + i; })
            .attr("d", arc)
            .attr("fill-rule", "evenodd")
            .style("fill", colour)
            .on("click", click);
        text = vis.selectAll("text").data(nodes);
        textEnter = text.enter().append("text")
            .style("fill-opacity", 1)
            .style("fill", function(d) {
                return brightness(d3.rgb(colour(d))) < 125 ? "#eee" : "#000";
            })
            .attr("text-anchor", function(d) {
                return x(d.x + d.dx / 2) > Math.PI ? "end" : "start";
            })
            .attr("dy", ".2em")
            .attr("transform", function(d) {
                var multiline = (d.name || "").split(" ").length > 1,
                    angle = x(d.x + d.dx / 2) * 180 / Math.PI - 90,
                    rotate = angle + (multiline ? -.5 : 0);
                return "rotate(" + rotate + ")translate(" + (y(d.y) + padding) + ")rotate(" + (angle > 90 ? -180 : 0) + ")";
            })
            .on("click", click);
        textEnter.append("tspan")
            .attr("x", 0)
            .text(function(d) { return d.depth ? d.name.split(" ")[0] : ""; });
        textEnter.append("tspan")
            .attr("x", 0)
            .attr("dy", "1em")
            .text(function(d) { return d.depth ? d.name.split(" ")[1] || "" : ""; });
        textEnter.append("tspan")
            .attr("x", 0)
            .attr("dy", "1em")
            .text(function(d) { return d.depth ? d.name.split(" ")[2] || "" : ""; });
        function click(d) {
            updateData(d);
        }

        $('.search_login').click(function(d) {
            var searchLoginName = $('.search_text').val();
            if (searchLoginName) {
                d.name = searchLoginName;
                updateData(d);
            }
        });

        $('#search-reg-username').click(function(d) {
            var searchLoginName = $('#next-reg-username').html();
            if (searchLoginName) {
                d.name = searchLoginName;
                updateData(d);
            }
        });

        $('.upstairs_tree').click(function(d) {
            d.name = $(this).attr('top-id');
            updateData(d);
        });

        $('.above_tree').click(function(d) {
            d.name = '';
            updateData(d);
        });

        $('.left_bottom_tree').click(function(d) {
            d.name = $(this).attr('lb-id');
            d.side = '1';
            updateData(d);
        });

        $('.right_bottom_tree').click(function(d) {
            d.name = $(this).attr('rb-id');
            d.side = '0';
            updateData(d);
        });

    });
    function isParentOf(p, c) {
        if (p === c) return true;
        if (p.children) {
            return p.children.some(function(d) {
                return isParentOf(d, c);
            });
        }
        return false;
    }
    function colour(d) {
        if (d.children) {
            // There is a maximum of two children!
            var colours = d.children.map(colour),
                a = d3.hsl(colours[0]),
                b = d3.hsl(colours[1]);
            // L*a*b* might be better here...
            return d3.hsl((a.h + b.h) / 2, a.s * 1.2, a.l / 1.2);
        }
        return d.colour || "#fff";
    }
// Interpolate the scales!
    function arcTween(d) {
        var my = maxY(d),
            xd = d3.interpolate(x.domain(), [d.x, d.x + d.dx]),
            yd = d3.interpolate(y.domain(), [d.y, my]),
            yr = d3.interpolate(y.range(), [d.y ? 20 : 0, radius]);
        return function(d) {
            return function(t) { x.domain(xd(t)); y.domain(yd(t)).range(yr(t)); return arc(d); };
        };
    }

    function maxY(d) {
        return d.children ? Math.max.apply(Math, d.children.map(maxY)) : d.y + d.dy;
    }
// http://www.w3.org/WAI/ER/WD-AERT/#color-contrast
    function brightness(rgb) {
        return rgb.r * .299 + rgb.g * .587 + rgb.b * .114;
    }

    function updateData(d) {
        if (d.name) {
            username = d.name.split(' ')[0];
        } else {
            username = username + '&back=true';
        }
        if (d.side) {
            username = username + '&side=' + d.side;
        }
        d3.json(location.protocol + '//' + location.hostname + "/business/team/take-json?id=" + username, function(error, json) {
            if (json.length > 0) {
                d3.select("svg")
                    .style("opacity", 1)
                    .transition().duration(1000).style("opacity", 0);

                username = json[0].name.split(' ')[0];
                var nodes = partition.nodes({children: json});
                vis.selectAll("path").remove();
                path = vis.selectAll("path").data(nodes);
                path.enter().append("path")
                    .attr("id", function (d, i) {
                        return "path-" + i;
                    })
                    .attr("d", arc)
                    .attr("fill-rule", "evenodd")
                    .style("fill", colour)
                    .on("click", click);
                vis.selectAll("text").remove();
                text = vis.selectAll("text").data(nodes);
                textEnter = text.enter().append("text")
                    .style("fill-opacity", 1)
                    .style("fill", function (d) {
                        return brightness(d3.rgb(colour(d))) < 125 ? "#eee" : "#000";
                    })
                    .attr("text-anchor", function (d) {
                        return x(d.x + d.dx / 2) > Math.PI ? "end" : "start";
                    })
                    .attr("dy", ".2em")
                    .attr("transform", function (d) {
                        var multiline = (d.name || "").split(" ").length > 1,
                            angle = x(d.x + d.dx / 2) * 180 / Math.PI - 90,
                            rotate = angle + (multiline ? -.5 : 0);
                        return "rotate(" + rotate + ")translate(" + (y(d.y) + padding) + ")rotate(" + (angle > 90 ? -180 : 0) + ")";
                    })
                    .on("click", click);
                textEnter.append("tspan")
                    .attr("x", 0)
                    .text(function (d) {
                        return d.depth ? d.name.split(" ")[0] : "";
                    });
                textEnter.append("tspan")
                    .attr("x", 0)
                    .attr("dy", "1em")
                    .text(function (d) {
                        return d.depth ? d.name.split(" ")[1] || "" : "";
                    });
                textEnter.append("tspan")
                    .attr("x", 0)
                    .attr("dy", "1em")
                    .text(function (d) {
                        return d.depth ? d.name.split(" ")[2] || "" : "";
                    });

                d3.select("svg")
                    .style("opacity", 0)
                    .transition().duration(1000).style("opacity", 1);

                function click(d) {
                    updateData(d);
                }
            }
        });
    }

}();