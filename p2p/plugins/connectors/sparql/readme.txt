Links

description of the api

http://museum-api.pbworks.com/w/page/21933437/Victoria%20and%20Albert%20Museum%20API%20Docs

direct search in the api

http://www.vam.ac.uk/api/json/museumobject/search?objectnamesearch=necklace

search in the push2press api

http://m.push2press.com/kitchensink/plugins/connectors/victoria_and_albert/search.php?srch=necklace



example p2p format

p2pitem: {
	title: "Necklace",
	subtitle: "necklace-unknown",
	identifier: 113825,
	object_number: "O144851",
	location: "Jewellery, room 91 mezzanine, case 75, shelf D, box 1",
	place: "naples",
	artist: "unknown",
	lat: "40.83990100",
	lon: "14.25185000",
	date_text: "1832-1835 (made)",
	collection_code: "MET",
	museum_number: "312-1868",
	thumb: "http://media.vam.ac.uk/media/thira/collection_images/2006BL/2006BL0323.jpg",
	thumb2: "http://m.push2press.com/kitchensink/timthumb.php?h=160&w=320&src=http://media.vam.ac.uk/media/thira/collection_images/2006BL/2006BL0323.jpg"
}



respurces
http://www.cambridgesemantics.com/semantic-university/sparql-by-example
http://www.bl.uk/schemas/
http://www.bl.uk/bibliographic/datafree.html#basicrdfxml
http://www.kb.nl/banners-apis-en-meer/dataservices-apis/middeleeuwse-verluchte-handschriften
http://collections.rmg.co.uk/solr/?q=name:Nelson&facet=on&facet.field=type
http://lucene.apache.org/solr/
http://lucene.apache.org/solr/4_3_0/tutorial.html
http://dbpedia.org/data/BBC.json
http://dbpedia.org/page/BBC
http://m.push2press.com/kitchensink/plugins/connectors/sparql/example.php?1
http://dbpedia.org/sparql
http://m.push2press.com/kitchensink/plugins/connectors/sparql/example1.php?1
http://www.openlinksw.com/blog/~kidehen/?id=1652
http://stackoverflow.com/questions/8788363/how-do-i-consume-a-sparql-endpoint-such-as-dbpedia-in-an-iphone-app



Semantic
http://jon651.glimworm.com/connectors/php_fetch/octest.php

fietspunten
http://amsterdam-maps.bma-collective.com/embed/parkeren/deploy_data/locaties.json
http://www.amsterdam.nl/parkeren-verkeer/fiets/fietsenstallingen/



some endpoints
endpoints
PREFIX bf: <http://schemas.talis.com/2006/bigfoot/configuration#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX uri: <http://api.talis.com/stores/bbc-wildlife>

SELECT DISTINCT * WHERE {
	?s ?p ?o
}
LIMIT 100



PREFIX bf: <http://schemas.talis.com/2006/bigfoot/configuration#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX uri: <http://api.talis.com/stores/bbc-wildlife>

SELECT DISTINCT ?namedgraph ?label
WHERE {
  GRAPH ?namedgraph { ?s ?p ?o }
  OPTIONAL { ?namedgraph rdfs:label ?label }
}
ORDER BY ?namedgraph



PREFIX bf: <http://schemas.talis.com/2006/bigfoot/configuration#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX uri: <http://api.talis.com/stores/bbc-wildlife>
SELECT DISTINCT * WHERE {
	?s ?p ?o
}
LIMIT 10



PREFIX foaf:  <http://xmlns.com/foaf/0.1/>
SELECT ?name
WHERE {
    ?person foaf:name ?name .
}



	▪	Talis endpoint <http://api.talis.com/stores/space/services/sparql>.
	▪	Apollo 7 known as <http://nasa.dataincubator.org/spacecraft/1968-089A>.

SELECT ?p ?o
{ 
  <http://nasa.dataincubator.org/spacecraft/1968-089A> ?p ?o
}





