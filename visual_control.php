<?php
// Set default data file
if(!isset($data_file))
{
  $data_file = "test.json";
}

if(!file_exists($data_file))
{
  echo "Data file does not exist.\n";
}

?>
<script src="js/d3.v3.js"></script>
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-migrate-1.0.0.js"></script>
<script>
// JQuery init slider
var cutoff = 5000;
$(function(cutoff) {
$("#size-slider").slider({max: 20000, min: 1, value: 5000, range: "max",
    slide: function(event, ui) {
        $("svg").remove();
        cutoff = ui.value;
        $("#size-val").text(ui.value);
        draw_tree(cutoff);
    }});
});

// D3js draw tree
draw_tree(cutoff);
function draw_tree(cutoff)
{
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

  d3.json(<?php echo "\"$data_file\"" ?>, function(root) {
    filter(root,cutoff);
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
        var id = d.name;
        var size = d.size;
        d3.select('#addChildren')
          .attr("href", function() { return "add.php?name=" + id; });
        d3.select('#delChildren')
          .attr("href", function() { return "delete.php?name=" + id; });
        d3.select('#updateNode')
          .attr("href", function() { return "update.php?name=" + id +"&size=" +size; });
      });

    node.append("text")
      .attr("id", function(d) {return d.name})
      .attr("dy", ".31em")
      .attr("text-anchor", function(d) { return d.x < 180 ? "start" : "end"; })
      .attr("transform", function(d) { return d.x < 180 ? "translate(8)" : "rotate(180)translate(-8)"; })
      .text(function(d) { return d.name; });
  });

  d3.select(self.frameElement).style("height", diameter - 150 + "px");
}

function filter(root,cutoff)
{
  var queue = [];
  var obj;
  queue.push(root);
  while(queue.length > 0)
  {
    if(queue[0].hasOwnProperty("children"))
    {
      var i = 0;
      while(i<queue[0]["children"].length)
      {
        if(queue[0]["children"][i]["size"] < cutoff)
          queue[0]["children"].splice(i,1);
        else
          i++;
      }
      for(i=0;i<queue[0]["children"].length;i++)
      {
        if(queue[0]["children"][i].hasOwnProperty("children"))
          queue.push(queue[0]["children"][i]);
      }
    }
    queue.shift();
  }  
}
</script>
<!-- Replaced by jquery-ui slider
<form method="post" action="filter.php">
  <label for="show_all">
    <input type="radio" checked="checked" name="radio_show" id="show_all" value="true">显示全部
  </label>
  <label for="show_part">
    <input type="radio" name="radio_show" id="show_part" value="false">显示部分
  </label> 
  <input type="submit" name="submit" value="Submit"/>
</form>
-->
<div>
Size cut off:<span id="size-val">5000</span>
<div id="size-slider" class="slider">
</div>
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