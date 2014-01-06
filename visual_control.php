<?php
// Set default data file
if(!isset($data_file))
{
  $data_file = "test.json";
}

if(!isset($_COOKIE['data_file']))
{
  $data_file = "test.json";
}
else
{
  $data_file = $_COOKIE['data_file'];
}

if(!file_exists($data_file))
{
  echo "Data file does not exist.\n";
}

?>
<script src="js/d3.v3.js"></script>
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.0.0.js"></script>
<script>
// JQuery init slider
var cutoff = 1;
var exchange_flag = false;
$(function(cutoff) {
$("#size-slider").slider({max: 20000, min: 1, value: 1, range: "max",
    slide: function(event, ui) {
        $("svg").remove();
        cutoff = ui.value;
        $("#size-val").text(ui.value);
        draw_tree(cutoff);
    }});
});

var node_selected = 0;
var node1 = null;
var node2 = null;
$(function() {
  $("#exchangeBtn").click(
    function() {
      $("circle").attr("r",6.5)
          .click( 
            function() {
              node_selected = node_selected + 1;
              if(node_selected == 1)
              {
                node1 = event.target.id;
              }
              if(node_selected == 2)
              {
                node2 = event.target.id;
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("GET","swap.php?node1="+node1+"&node2="+node2,true);
                //xmlhttp.open("GET","delete.php?name="+node1,false);
                xmlhttp.send();
                $("circle").attr("r",4.5);
                $("svg").remove();
                draw_tree(cutoff);
                node_selected = 0;
              }
          });;
    }
    )
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
      .attr("id",function(d) { return d.name;})
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

    node.append("text")
      .attr("id", function(d) {return d.name})
      .attr("dy", ".31em")
      .attr("text-anchor", function(d) { return d.x < 180 ? "start" : "end"; })
      .attr("transform", function(d) { return d.x < 180 ? "translate(8)" : "rotate(180)translate(-8)"; })
      .text(function(d) { return d.name; })
      .on("mouseover",show_defination)
      .on("click",text_click);
  });

  d3.select(self.frameElement).style("height", diameter - 150 + "px");
}

// Show defination of the current item
function show_defination(d) {
    d3.select(this)
        .append("svg:title")
        .text(function(d) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("GET","query.php?name="+d.name,false);
            xmlhttp.send();
            return xmlhttp.responseText;
          })
        .attr("x",function(d) {return d.x+10;})
        .attr("y",function(d) {return d.y+10;})
}

// Text clicked , show the sorted children
function text_click(d) {
  d3.select("#datatable table").remove();
  var rows = [];
  d.children.forEach(function(child) {
    rows.push(child);
  });
  rows.sort(sortSize);
  var table = d3.select("#datatable").append("table"),
      thead = table.append("thead"),
      tbody = table.append("tbody");
  thead.append("th").text("Name");
  thead.append("th").text("Size");
  var tr = tbody.selectAll("tr")
      .data(rows)
      .enter()
      .append("tr");
  var td =  tr.selectAll("td")
      .data(function(d) { return [d.name, d.size]; })
      .enter().append("td")
      .text(function(d) { return d; });
}

// sort func to sort by size for array.sort
function sortSize(a,b)
{
  return a.size - b.size;
}

// Function to decide which node should be displayed
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
<div id="control-panel">
Size cut off:<span id="size-val">1</span>
<div id="size-slider" class="slider">
</div>
<div id="datatable"></div>
<div id="divContext"
 style="border: 1px solid blue; display: none;width:150px;">
    <ul class="cmenu">
        <li><a id="addChildren">增加分支</a></li>
        <li><a id="delChildren">删除分支</a></li>
        <li><a id="updateNode">修改节点</a></li>
        <li><a id="showSubVis">显示子图</a></li>
        <li class="topSep">
            <a id="aDisable" href="#">disable this menu</a>
        </li>
    </ul>
</div>
<div id = "node-exchange">
  <button type="button" id="exchangeBtn">交换节点</button>
</div>
</div>