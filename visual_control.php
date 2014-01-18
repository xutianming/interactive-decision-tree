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
<script src="js/util.js"></script>
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

// Bind click event to exchange button
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

// Bind click event to add attribute button
// Migrate from add.php
$(function()
{
var i = 1;
$('#add-addAttrBtn').click(
  function(){
    var dataform = $('#add-dataform');
    $('<label for="attributename'+i+'">Attribute Name:</label></br>' + 
      '<input id="attributename'+i+'" name="attributename'+i+'" type="text" size="30"/></br>'+
      '<label for="attributevalue'+i+'">Attribute Value:</label></br>'+
      '<input id="attributevalue'+i+'" name="attributevalue'+i+'" type="text" size="30"/></br>')
    .appendTo(dataform);
    $('#add-optionalattributenum').attr('value',i);
    i++;
  });
});

// Bind click event to add attribute button
// Migrate from update.php
$(function()
{
$('#update-addAttrBtn').click(
  function(){
    var j = parseInt($('#update-optionalattributenum').attr('value'))+1;
    var dataform = $('#update-dataform');
    $('<label for="attributename'+j+'">Attribute Name:</label></br>' + 
      '<input id="attributename'+j+'" name="attributename'+j+'" type="text" size="30"/></br>'+
      '<label for="attributevalue'+j+'">Attribute Value:</label></br>'+
      '<input id="attributevalue'+j+'" name="attributevalue'+j+'" type="text" size="30"/></br>')
    .appendTo(dataform);
    $('#update-optionalattributenum').attr('value',j);
    j++;
  });
});

// D3js draw tree
draw_tree(cutoff);



function draw_tree(cutoff)
{
  var diameter = 840;

  var tree = d3.layout.tree()
    .size([360, diameter / 2 - 120])
    .separation(function(a, b) { return (a.parent == b.parent ? 1 : 2) / a.depth; });

  var diagonal = d3.svg.diagonal.radial()
    .projection(function(d) { return [d.y, d.x / 180 * Math.PI]; });

  var drag = d3.behavior.drag()
      .on("drag", function() {
        d3.select('#main-graph')
          .transition()
          .duration(750)
          .attr("transform","translate(0,0)");
        d3.select('#sub-graph')
          .transition()
          .duration(750)
          .attr("transform","translate(250,250)");
      });

  var svg = d3.select("#graph").append("svg")
    .attr("width", 1500)
    .attr("height", 800)
    .append("g")
    .attr("id","main-graph")
    .attr("transform", "translate(" + diameter / 2 + "," + diameter / 2 + ")")
    .attr("x",diameter / 2)
    .attr("y",diameter / 2)
    .call(drag);

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
          .on("click", function() {
            d3.select('#add-form')
              .style('display', 'block');
            d3.select('#update-form')
              .style('display', 'none');
            d3.select('#add-inputparent')
              .attr('value',id);
            d3.select('#message')
              .text("请填写屏幕右侧的表单，完成后点击提交按钮。");
          });
        d3.select('#delChildren')
          .on("click", function() {
            d3.select('#add-form')
              .style('display', 'none');
            d3.select('#update-form')
              .style('display', 'none');
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("GET","delete.php?name="+id,false);
            xmlhttp.send();
            d3.select('svg').remove();
            draw_tree(cutoff);
            d3.select('#message')
              .text("删除成功。")
          });
        d3.select('#updateNode')
          .on("click", function() { 
            d3.select('#update-form')
              .style('display', 'block');
            d3.select('#add-form')
              .style('display', 'none');
            d3.select('#update-originname')
              .attr('value',id);
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("GET","query.php?name="+id,false);
            xmlhttp.send();
            var response_str = parseResponse2DataForm(xmlhttp.responseText);
            var dataform = $('#update-dataform');
            $(response_str).appendTo(dataform);
            d3.select('#message')
              .text("请填写屏幕右侧的表单，完成后点击提交按钮。") 
          });
        d3.select('#showSubVis')
          .on("click", function() {
            /**
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("GET","details.php?name="+id,false);
            xmlhttp.send();
            var response_str = xmlhttp.responseText;
            var sub_graph = $('#sub-graph');
            $(response_str).appendTo(sub_graph);
            */
            d3.select('#sub-graph').remove();
            draw_sub_tree(id);
          });
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

function draw_sub_tree(name)
{
  var margin = {top: 200, right: 10, bottom: 10, left: 700},
    width = 460 - margin.right - margin.left,
    height = 700 - margin.top - margin.bottom;
    
  var i = 0,
    duration = 750,
    root;

  var tree = d3.layout.tree()
      .size([height, width]);

  var diagonal = d3.svg.diagonal()
      .projection(function(d) { return [d.y, d.x]; });

  var drag = d3.behavior.drag()
      .on("drag", function() {
        d3.select('#graph g')
          .transition()
          .duration(750)
          .attr("transform","translate(0,0)");
        d3.select('#sub-graph g')
          .transition()
          .duration(750)
          .attr("transform","translate(-250,-250)");
      });
      /**
  var svg = d3.select("#graph").append("svg")
    .attr("width", width + margin.right + margin.left)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    //.call(drag);
  */
  var svg = d3.select("#graph svg")
              .append("g")
              .attr("id","sub-graph")
              .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
  d3.json("test.json", function(error, flare) {
    root = get_sub_tree(flare,name);
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

  d3.select(self.frameElement).style("height", "350px");

  function update(source) {

  // Compute the new tree layout.
  var nodes = tree.nodes(root).reverse(),
      links = tree.links(nodes);

  // Normalize for fixed-depth.
  nodes.forEach(function(d) { d.y = d.depth * 180; });

  // Update the nodes…
  var node = svg.selectAll("#sub-graph g.node")
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
function get_sub_tree(flare,name)
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
}


</script>
<!--
<div id="control-panel">
  Size cut off:<span id="size-val">1</span>
  <div id="size-slider" class="slider">
  </div>
</div>
-->

<!--
<div id = "node-exchange">
  <button type="button" id="exchangeBtn">交换节点</button>
</div>
-->
<!--
<div id="edit-form">
  <div id="add-form" style="display: none;">
    <h2>Please enter the information of the child node.</h2>
    <form method="post" action="add.php">
      <div id = "add-dataform">
        <label for="inputname">Name:</label><br/>
        <input id="add-inputname" name="inputname" type="text" size="30"/><br/>
        <label for="inputsize">Size:</label><br/>
        <input id="add-inputsize" name="inputsize" type="text" size="10"/><br/>
        <label for="inputparent">Parent:</label></br>
        <input id="add-inputparent" name="inputparent" type="text" size="30" readonly="readonly"/><br/>
        <input id="add-optionalattributenum" name="optionalattributenum" style="display:none;" value="0"/>
      </div>
      <input type="submit" name="submit" value="Submit"/>
    </form>
    <button type="button" id="add-addAttrBtn">增加属性</button>
  </div>
  <div id="update-form" style="display: none;">
    <h2>Please enter the information to update.</h2>
    <form method="post" action="update.php">
      <div id = "update-dataform">
        <label for="inputname">Name:</label><br/>
        <input id="update-inputname" name="inputname" type="text" size="30"/><br/>
        <label for="inputsize">Size:</label><br/>
        <input id="update-inputsize" name="inputsize" type="text" size="10"/><br/>
        <label for="originname">Origin Name:</label><br/>
        <input id="update-originname" name="originname" type="text" size="30" readonly="readonly" /><br/>
      </div>
      <input type="submit" name="submit" value="Submit"/>
      <button type="button" id="update-addAttrBtn">增加属性</button>
    </form>
  </div>
</div>
-->