<%-- 
    Document   : ViewPosts
    Created on : Jun 20, 2015, 8:59:07 PM
    Author     : Adam Harris
--%>

<%@page import="java.io.IOException"%>
<%@page import="java.io.FileReader"%>
<%@page import="java.io.BufferedReader"%>
<%@page import="java.io.File"%>
<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>View the Discussion</title>
        <style>
            body{
                background-color: black;
            }
            h1, h3, p , a{
	font-family: Consolas, monaco, monospace;
        color:green;
	font-size: 24px;
	font-style: normal;
	font-variant: normal;
	font-weight: 500;
	line-height: 26.3999996185303px;
        }
        h3, p, a{
            font-size:14px;
        }
        a{
            color:blue;
        }
        </style>
    </head>
    <body>
        <h1>Discussion Posts</h1>
        <a href="newPost.jsp">Submit a New Post</a>
        <br/>
        <br/>
        <%
        //Reads from a file
            try {
            String directory = System.getenv("OPENSHIFT_DATA_DIR");
            String fileName = "DiscussionData.txt";
            if (directory != null) {
                fileName = directory + "/" + fileName;
            }

            File file = new File(fileName);
            file.createNewFile();
            BufferedReader reader = new BufferedReader(new FileReader(file));

            String line;
            String text = "";

            while ((line = reader.readLine()) != null) {
                text += line;                
            }
            
            out.println(text);

        } catch (IOException e) {
            e.printStackTrace();
        } 
        %>
    </body>
</html>
