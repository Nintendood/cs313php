/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.discussion;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.PrintWriter;
import java.text.SimpleDateFormat;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/**
 *
 * @author Adam Harris
 */
@WebServlet(name = "CreatePost", urlPatterns = {"/CreatePost"})
public class CreatePost extends HttpServlet {
    

    /**
     * Processes requests for both HTTP <code>GET</code> and <code>POST</code>
     * methods.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        response.setContentType("text/html;charset=UTF-8");
        try (PrintWriter out = response.getWriter()) {
            String username = request.getParameter("username");
            String comment = request.getParameter("comment");
            request.setAttribute("username", username);
            request.setAttribute("comment", comment);
            
            String timeStamp = new SimpleDateFormat("HH:mm.ss MM/dd/yyyy").format(new java.util.Date());
            
            String newPost = "<div><h3>" + username + " at " + timeStamp + ":</h3><p>" + comment + "</p><div><br/>";
            
            String directory = System.getenv("OPENSHIFT_DATA_DIR");
            String fileName = "DiscussionData.txt";
            
            String text = "";
            
            //Reads from a file
            try {
            if (directory != null) {
                fileName = directory + "/" + fileName;
            }

            File file = new File(fileName);
            file.createNewFile();
            BufferedReader reader = new BufferedReader(new FileReader(file));

            String line;
            
            while ((line = reader.readLine()) != null) {
                newPost += line;                
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
            
            //Write to  a file
            try{
                if (directory != null)
                {
                    fileName = directory + "/" + fileName;
                }
                File file = new File(fileName);
                
                BufferedWriter writer = new BufferedWriter(new FileWriter(file));
                writer.write(newPost);
                writer.close();
            } catch (IOException e){
                e.printStackTrace();
            }
            response.sendRedirect("ViewPosts.jsp");
        }
    }

    // <editor-fold defaultstate="collapsed" desc="HttpServlet methods. Click on the + sign on the left to edit the code.">
    /**
     * Handles the HTTP <code>GET</code> method.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        processRequest(request, response);
    }

    /**
     * Handles the HTTP <code>POST</code> method.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        processRequest(request, response);
    }

    /**
     * Returns a short description of the servlet.
     *
     * @return a String containing servlet description
     */
    @Override
    public String getServletInfo() {
        return "Short description";
    }// </editor-fold>

}
