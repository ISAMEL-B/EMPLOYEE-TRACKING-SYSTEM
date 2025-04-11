import React from "react";
import { Card, CardContent } from "@/components/ui/card";
import { Tabs, TabsList, TabsTrigger, TabsContent } from "@/components/ui/tabs";
import { BarChart, PieChart, RadarChart, HeatMap } from "lucide-react";
import { Button } from "@/components/ui/button";

const FacultyPerformance = () => {
  return (
    <div className="p-6 bg-gray-900 min-h-screen text-white">
      <h1 className="text-3xl font-bold mb-6">Faculty Performance Dashboard</h1>

      {/* Filters */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <select className="p-2 rounded bg-gray-800 text-white border border-gray-700">
          <option>Select Faculty</option>
          <option>Faculty of Science</option>
          <option>Faculty of Medicine</option>
          <option>Faculty of Computing</option>
        </select>

        <select className="p-2 rounded bg-gray-800 text-white border border-gray-700">
          <option>Academic Year</option>
          <option>2024/2025</option>
          <option>2023/2024</option>
        </select>

        <select className="p-2 rounded bg-gray-800 text-white border border-gray-700">
          <option>Department</option>
          <option>Biology</option>
          <option>Chemistry</option>
          <option>Computer Science</option>
        </select>

        <Button className="bg-blue-600 hover:bg-blue-700">Generate Report</Button>
      </div>

      {/* Tabs Section */}
      <Tabs defaultValue="overview" className="w-full">
        <TabsList className="bg-gray-800">
          <TabsTrigger value="overview">Overview</TabsTrigger>
          <TabsTrigger value="academic">Academic</TabsTrigger>
          <TabsTrigger value="research">Research & Innovation</TabsTrigger>
          <TabsTrigger value="community">Community Service</TabsTrigger>
        </TabsList>

        {/* Overview */}
        <TabsContent value="overview">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            <Card className="bg-gray-800">
              <CardContent className="p-4">
                <p className="text-lg font-semibold mb-2">Category Distribution</p>
                <div className="h-48 flex items-center justify-center">
                  <PieChart className="w-16 h-16 text-purple-400" />
                </div>
              </CardContent>
            </Card>

            <Card className="bg-gray-800">
              <CardContent className="p-4">
                <p className="text-lg font-semibold mb-2">Performance Trend</p>
                <div className="h-48 flex items-center justify-center">
                  <BarChart className="w-16 h-16 text-blue-400" />
                </div>
              </CardContent>
            </Card>

            <Card className="bg-gray-800">
              <CardContent className="p-4">
                <p className="text-lg font-semibold mb-2">Category Radar</p>
                <div className="h-48 flex items-center justify-center">
                  <RadarChart className="w-16 h-16 text-green-400" />
                </div>
              </CardContent>
            </Card>
          </div>
        </TabsContent>

        {/* Academic Tab */}
        <TabsContent value="academic">
          <div className="mt-6">
            <Card className="bg-gray-800">
              <CardContent className="p-4">
                <p className="text-lg font-semibold mb-4">Academic Performance Breakdown</p>
                <div className="h-64 flex items-center justify-center">
                  <BarChart className="w-20 h-20 text-yellow-400" />
                </div>
              </CardContent>
            </Card>
          </div>
        </TabsContent>

        {/* Research & Innovation Tab */}
        <TabsContent value="research">
          <div className="mt-6">
            <Card className="bg-gray-800">
              <CardContent className="p-4">
                <p className="text-lg font-semibold mb-4">Research & Innovation Contributions</p>
                <div className="h-64 flex items-center justify-center">
                  <RadarChart className="w-20 h-20 text-pink-400" />
                </div>
              </CardContent>
            </Card>
          </div>
        </TabsContent>

        {/* Community Service Tab */}
        <TabsContent value="community">
          <div className="mt-6">
            <Card className="bg-gray-800">
              <CardContent className="p-4">
                <p className="text-lg font-semibold mb-4">Community Engagement Overview</p>
                <div className="h-64 flex items-center justify-center">
                  <HeatMap className="w-20 h-20 text-red-400" />
                </div>
              </CardContent>
            </Card>
          </div>
        </TabsContent>
      </Tabs>
    </div>
  );
};

export default FacultyPerformance;
