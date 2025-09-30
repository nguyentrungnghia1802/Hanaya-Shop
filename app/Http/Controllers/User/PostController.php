<?php

/**
 * User Post Controller
 *
 * This controller handles blog post viewing functionality for customers in the Hanaya Shop
 * e-commerce application. It provides access to published blog content including post
 * listings, search functionality, and detailed post views for content marketing and
 * customer engagement.
 *
 * Key Features:
 * - Published post listing with pagination
 * - Post search functionality across title and content
 * - Individual post detailed view
 * - Author information display
 * - SEO-friendly URLs and metadata
 * - Content filtering for published posts only
 *
 * Content Strategy:
 * - Supports content marketing initiatives
 * - Improves SEO through blog content
 * - Enhances customer engagement
 * - Provides product education and inspiration
 * - Builds brand authority and trust
 *
 * @author Hanaya Shop Development Team
 *
 * @version 1.0
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

/**
 * Post Controller Class
 *
 * Manages customer-facing blog functionality including post viewing,
 * search capabilities, and content engagement features.
 */
class PostController extends Controller
{
    /**
     * Display Published Post Listing
     *
     * Retrieves and displays all published blog posts with search functionality
     * and pagination. Only shows posts marked as active/published to ensure
     * content quality and prevent access to draft content.
     *
     * Features:
     * - Published posts only (status = true)
     * - Author information for credibility
     * - Search across post titles and content
     * - Pagination for performance (10 posts per page)
     * - Search parameter preservation in pagination
     *
     * @param  \Illuminate\Http\Request  $request  HTTP request with optional search parameters
     * @return \Illuminate\View\View Post index view with filtered and paginated posts
     */
    public function index(Request $request)
    {
        // Base Query for Published Posts
        /**
         * Published Post Query - Retrieve only active blog posts
         * Filters for published status and includes author relationships
         * Ensures only quality, approved content is displayed to customers
         */
        $query = Post::where('status', true)->with('author');

        // Search Functionality
        /**
         * Content Search - Search across post titles and content
         * Enables customers to find relevant blog content
         * Searches both title and content fields for comprehensive results
         */
        if ($request->has('search') && $request->input('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('content', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Pagination with Ordering
        /**
         * Post Pagination - Order by creation date and paginate results
         * Newest posts first for relevant content discovery
         * 10 posts per page for optimal loading performance
         */
        $posts = $query->orderByDesc('created_at')->paginate(10);

        // Search Parameter Preservation
        /**
         * Pagination Search Preservation - Maintain search parameters across pages
         * Ensures search context is preserved when navigating paginated results
         * Improves user experience during content discovery
         */
        if ($request->has('search')) {
            $posts->appends(['search' => $request->input('search')]);
        }

        return view('page.posts.index', compact('posts'));
    }

    /**
     * Display Individual Post Details
     *
     * Shows complete details for a specific published blog post including
     * author information and full content. Ensures only published posts
     * are accessible and provides comprehensive post information.
     *
     * Features:
     * - Published post validation
     * - Complete post content display
     * - Author information and credibility
     * - SEO-optimized individual post pages
     *
     * @param  int  $id  Post ID to display
     * @return \Illuminate\View\View Post detail view with complete post information
     */
    public function show($id)
    {
        // Secure Post Retrieval
        /**
         * Published Post Validation - Ensure post exists and is published
         * Validates both post existence and published status
         * Prevents access to draft or unpublished content
         * Includes author relationship for complete post context
         */
        $post = Post::where('id', $id)->where('status', true)->with('author')->firstOrFail();

        return view('page.posts.show', compact('post'));
    }
}
