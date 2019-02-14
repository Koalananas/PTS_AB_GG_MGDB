import urllib2
import json
contents = urllib2.urlopen("http://localhost:8080/pts_ab_gg_mgdb/test.php").read()

data  = json.loads(contents)

maxi = len(data)

for row in data :
    if int(row) >maxi : maxi = int(row)
newdata = []
'''
for row in data :
    temp = []
    for i in range(0, maxi+1):
        temp.append(0)
    #print(temp)
    keys = row.keys()
    for key in keys:
        numkey = int(key) 
        temp[numkey] = row[key]
        
    newdata.append(temp)
    print(len(temp))
print(len(newdata))
'''
for i in range(0, maxi):
    newdata.append([])

for row in data :
    temp = []
    for i in range(0, maxi) :
        temp.append(0)
    keys = data[row].keys()
    for key in keys :
        numkeycol = int(key)
        temp[numkeycol -1] = data[row][key]
    numkeyrow = int(row)
    try :
        newdata[numkeyrow-1] = temp
    except :
        #print numkeyrow
        a = 1

for r in newdata:
    print r
# Python program for implementation of Ford Fulkerson algorithm 
   
from collections import defaultdict 
   
#This class represents a directed graph using adjacency matrix representation 
class Graph: 
   
    def __init__(self,graph): 
        self.graph = graph # residual graph 
        self. ROW = len(graph) 
        #self.COL = len(gr[0]) 
          
   
    '''Returns true if there is a path from source 's' to sink 't' in 
    residual graph. Also fills parent[] to store the path '''
    def BFS(self,s, t, parent): 
  
        # Mark all the vertices as not visited 
        visited =[False]*(self.ROW) 
          
        # Create a queue for BFS 
        queue=[] 
          
        # Mark the source node as visited and enqueue it 
        queue.append(s) 
        visited[s] = True
           
         # Standard BFS Loop 
        while queue: 
  
            #Dequeue a vertex from queue and print it 
            u = queue.pop(0) 
          
            # Get all adjacent vertices of the dequeued vertex u 
            # If a adjacent has not been visited, then mark it 
            # visited and enqueue it 
            for ind, val in enumerate(self.graph[u]): 
                if visited[ind] == False and val > 0 : 
                    queue.append(ind) 
                    visited[ind] = True
                    parent[ind] = u 
  
        # If we reached sink in BFS starting from source, then return 
        # true, else false 
        return True if visited[t] else False
              
      
    # Returns tne maximum flow from s to t in the given graph 
    def FordFulkerson(self, source, sink): 
  
        # This array is filled by BFS and to store path 
        parent = [-1]*(self.ROW) 
  
        max_flow = 0 # There is no flow initially 
  
        # Augment the flow while there is path from source to sink 
        while self.BFS(source, sink, parent) : 
  
            # Find minimum residual capacity of the edges along the 
            # path filled by BFS. Or we can say find the maximum flow 
            # through the path found. 
            path_flow = float("Inf") 
            s = sink 
            while(s !=  source): 
                path_flow = min (path_flow, self.graph[parent[s]][s]) 
                s = parent[s]
                #print(s)
            print('The flow for this path is %d' % path_flow)
  
            # Add path flow to overall flow 
            max_flow +=  path_flow 
  
            # update residual capacities of the edges and reverse edges 
            # along the path 
            v = sink 
            while(v !=  source): 
                u = parent[v]
                try :
                    self.graph[u][v] -= path_flow 
                    self.graph[v][u] += path_flow 
                    v = parent[v]
                except :
                    print('error')
                    print(u)
                    print(v)
  
        return max_flow 
  
   
# Create a graph given in the above diagram 
  
graph = [[0, 9, 13, 0, 0, 0], 
        [0, 0, 10, 12, 0, 0], 
        [0, 4, 0, 0, 14, 0], 
        [0, 0, 9, 0, 0, 20], 
        [0, 0, 0, 7, 0, 4], 
        [0, 0, 0, 0, 0, 0]]

graph = newdata  
g = Graph(graph) 
  
source = 0; sink = 5
   
print ("The maximum possible flow is %d " % g.FordFulkerson(source, sink)) 
  
#This code is contributed by Neelam Yadav 

