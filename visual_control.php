<script src="http://d3js.org/d3.v3.min.js"></script>
<script>

var diameter = 960;

var tree = d3.layout.tree()
    .size([360, diameter / 2 - 120])
    .separation(function(a, b) { return (a.parent == b.parent ? 1 : 2) / a.depth; });

var diagonal = d3.svg.diagonal.radial()
    .projection(function(d) { return [d.y, d.x / 180 * Math.PI]; });

var svg = d3.select("body").append("svg")
    .attr("width", diameter)
    .attr("height", diameter - 150)
  .append("g")
    .attr("transform", "translate(" + diameter / 2 + "," + diameter / 2 + ")");

d3.json("flare.json", function(error, root) {
  var nodes = tree.nodes(root),
      links = tree.links(nodes);

  var link = svg.selectAll(".link")
      .data(links)
      .enter().append("path")
      .attr("class", "link")
      .attr("d", diagonal);

  var node = svg.selectAll(".node")
      .data(nodes)
      .enter().append("g")
      .attr("class", "node")
      .attr("transform", function(d) { return "rotate(" + (d.x - 90) + ")translate(" + d.y + ")"; });

  node.append("circle")
      .attr("r", 4.5)
      .on("contextmenu",function(d,index) {
        if (d3.event.pageX || d3.event.pageY) {
            var x = d3.event.pageX;
            var y = d3.event.pageY;
        } else if (d3.event.clientX || d3.event.clientY) {
          var x = d3.event.clientX + document.body.scrollLeft + documentElement.scrollLeft;
          var y = d3.event.clientY + document.body.scrollTop + documentElement.scrollTop;
        }

        d3.event.preventDefault();

        d3.select('#divContext')
          .style('position', 'absolute')
          .style('left', x + "px")
          .style('top', y + "px")
          .style('display', 'block')
          .on("click",function() {
            d3.select(this)
              .style('display', 'none');
          });
        console.log(d);
        var id = d.name;
        d3.select('#addChildren')
          .attr("href", function() { return "add.php?id=" + id; });
        d3.select('#delChildren')
          .attr("href", function() { return "delete.php?id=" + id; });
        d3.select('#updateNode')
          .attr("href", function() { return "update.php?id=" + id; });
      });

  node.append("text")
      .attr("dy", ".31em")
      .attr("text-anchor", function(d) { return d.x < 180 ? "start" : "end"; })
      .attr("transform", function(d) { return d.x < 180 ? "translate(8)" : "rotate(180)translate(-8)"; })
      .text(function(d) { return d.name; });
});

d3.select(self.frameElement).style("height", diameter - 150 + "px");
</script>
<div id="divContext"
 style="border: 1px solid blue; display: none;">
    <ul class="cmenu">
        <li><a id="addChildren">增加分支</a></li>
        <li><a id="delChildren">删除分支</a></li>
        <li><a id="updateNode">修改节点</a></li>
        <li class="topSep">
            <a id="aDisable" href="#">disable this menu</a>
        </li>
    </ul>
</div>