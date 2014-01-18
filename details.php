<?php
require_once('admin/login.php');
if(!empty($_COOKIE['user_id']))
{
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<link rel="stylesheet" href="styles.css"/>
<link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.min.css"/>
<link rel="stylesheet" href="css/jquery-ui.css">
</head>
<body>
<?php
if (isset($_GET['name']))
{
	$name = $_GET['name'];
}
else
{
	echo '<p class="info">Sorry,no node was specified for showing.</p>';
}
?>
<script src="js/d3.v3.js"></script>
<script>
var name = null;
<?php
if(isset($name)) 
{
?>
name = <?php echo "\"$name\"";?>;
<?php
}
?>

console.log(name);

var margin = {top: 30, right: 10, bottom: 100, left: 600},
    width = 960 - margin.right - margin.left,
    height = 700 - margin.top - margin.bottom;
    
var i = 0,
    duration = 750,
    root;

var tree = d3.layout.tree()
    .size([height, width]);

var diagonal = d3.svg.diagonal()
    .projection(function(d) { return [d.y, d.x]; });

var svg = d3.select("#sub-graph").append("svg")
    .attr("width", width + margin.right + margin.left)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

d3.json("test.json", function(error, flare) {
  root = filter(flare,name);
  root.x0 = height / 2;
  root.y0 = 0;

  function collapse(d) {
    if (d.children) {
      d._children = d.children;
      d._children.forEach(collapse);
      d.children = null;
    }
  }

  root.children.forEach(collapse);
  update(root);

});

d3.select(self.frameElement).style("height", "800px");

function update(source) {

  // Compute the new tree layout.
  var nodes = tree.nodes(root).reverse(),
      links = tree.links(nodes);

  // Normalize for fixed-depth.
  nodes.forEach(function(d) { d.y = d.depth * 180; });

  // Update the nodes…
  var node = svg.selectAll("g.node")
      .data(nodes, function(d) { return d.id || (d.id = ++i); });

  // Enter any new nodes at the parent's previous position.
  var nodeEnter = node.enter().append("g")
      .attr("class", "node")
      .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
      .on("click", click);

  nodeEnter.append("circle")
      .attr("r", 1e-6)
      .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; })
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
        d3.select('#showSubVis')
          .attr("href", function() { return "details.php?name=" + id; });
      });

  nodeEnter.append("text")
      .attr("x", function(d) { return d.children || d._children ? -10 : 10; })
      .attr("dy", ".35em")
      .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
      .text(function(d) { return d.name; })
      .style("fill-opacity", 1e-6);

  // Transition nodes to their new position.
  var nodeUpdate = node.transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

  nodeUpdate.select("circle")
      .attr("r", 4.5)
      .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

  nodeUpdate.select("text")
      .style("fill-opacity", 1);

  // Transition exiting nodes to the parent's new position.
  var nodeExit = node.exit().transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
      .remove();

  nodeExit.select("circle")
      .attr("r", 1e-6);

  nodeExit.select("text")
      .style("fill-opacity", 1e-6);

  // Update the links…
  var link = svg.selectAll("path.link")
      .data(links, function(d) { return d.target.id; });

  // Enter any new links at the parent's previous position.
  link.enter().insert("path", "g")
      .attr("class", "link")
      .attr("d", function(d) {
        var o = {x: source.x0, y: source.y0};
        return diagonal({source: o, target: o});
      });

  // Transition links to their new position.
  link.transition()
      .duration(duration)
      .attr("d", diagonal);

  // Transition exiting nodes to the parent's new position.
  link.exit().transition()
      .duration(duration)
      .attr("d", function(d) {
        var o = {x: source.x, y: source.y};
        return diagonal({source: o, target: o});
      })
      .remove();

  // Stash the old positions for transition.
  nodes.forEach(function(d) {
    d.x0 = d.x;
    d.y0 = d.y;
  });
}

// Toggle children on click.
function click(d) {
  if (d.children) {
    d._children = d.children;
    d.children = null;
  } else {
    d.children = d._children;
    d._children = null;
  }
  update(d);
}

// Function to filter out sub-tree of root
function filter(flare,name)
{
  if(name == null || name == "flare")
  	return flare;
  var queue = [];
  var obj;
  queue.push(flare);
  while(queue.length > 0)
  {
    if(queue[0].hasOwnProperty("children"))
    {
      var i = 0;
      while(i<queue[0]["children"].length)
      {
        if(queue[0]["children"][i]["name"] == name)
          return queue[0]["children"][i];
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
  return flare;  
}

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
</body>
</html>
<?php
}
?>